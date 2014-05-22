<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Quote as QuoteService;
use DateTime;

class QuoteController
{
	public function edit(Request $request, Application $app)
	{	
		$projectId = $request->get('projectId');
		$quoteId = $request->get('quoteId');

		if ( !isset($projectId) && !isset($quoteId) ) {
			throw new Exception("Invalid Parameters", 1);
		}
		$quote = null;
		$project = null;

		$quoteEquipmentResources = array();
		$quoteServiceResources = array();
		$quoteHumanResources = array();

		$shareCollection = array();

		if ( isset($projectId) ) {
			$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
			$version = count($project->getQuoteCollection()) + 1;
		} else {
			$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
			$project = $quote->getProject();
			$version = $quote->getVersion();

			$quoteResources = $quote->getResourceQuoteCollection();
			if ( count($quoteResources) > 0) {
				foreach ($quoteResources as $quoteResource) {
					$resource = $quoteResource->getResource();
					$type = $resource->getType();
					switch (get_class($type)) {

					 	case 'Orcamentos\Model\EquipmentType':
					 		$quoteEquipmentResources[] = $quoteResource;
					 		break;

					 	case 'Orcamentos\Model\ServiceType':
					 		$quoteServiceResources[] = $quoteResource;
					 		break;

					 	case 'Orcamentos\Model\HumanType':
					 		$quoteHumanResources[] = $quoteResource;
					 		break;
					};
				}
			}
		}

		$equipmentResources = array();
		$serviceResources = array();
		$humanResources = array();

		$resources = $project->getCompany()->getResourceCollection();

		foreach ($resources as $resource) {
			$type = $resource->getType();
			switch (get_class($type)) {

			 	case 'Orcamentos\Model\EquipmentType':
			 		$equipmentResources[] = $resource;
			 		break;

			 	case 'Orcamentos\Model\ServiceType':
			 		$serviceResources[] = $resource;
			 		break;

			 	case 'Orcamentos\Model\HumanType':
			 		$humanResources[] = $resource;
			 		break;
			};
		}

		return $app['twig']->render('quote/edit.twig',
			array(
				'quoteEquipmentResources' => $quoteEquipmentResources,
				'quoteServiceResources' => $quoteServiceResources,
				'quoteHumanResources' => $quoteHumanResources,
				'equipmentResources' => $equipmentResources,
				'serviceResources' => $serviceResources,
				'humanResources' => $humanResources,
				'quote' => $quote,
				'project' => $project,
				'version' => $version
			)
		);
	}

	public function detail(Request $request, Application $app, $quoteId)
	{	
		$resourceCollection = null;
		
		if ( !isset($quoteId) ) {
			throw new Exception("Parâmetros inválidos", 1);
		}
		
		$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);

		$resourceCollection = $quote->getResourceQuoteCollection();

		$result = $this->quoteCalculateCost($quote);

		$final = $result['final'];
		$commission = $result['commission'];
		$profit = $result['profit'];
		$taxes = $result['taxes'];

		$shareCollection = $quote->getShareCollection();
		
		$shareNotesCollection = array();

		foreach ($shareCollection as $sc) {
			$notes = $sc->getShareNotesCollection();
			foreach ($notes as $note) {
				$shareNotesCollection[] = $note;
			}
		}

		if( count($shareNotesCollection) > 0 ) {
			usort($shareNotesCollection, $app['sortCreated']);
		}
		
		switch ($quote->getStatus()) {
			case 1:
			case '1':
				$status = 'Esperando';
				break;
			
			case 2:
			case '2':
				$status = 'Aprovado';
				break;

			case 3:
			case '3':
				$status = 'Não aprovado';
				break;
		}

		return $app['twig']->render('quote/detail.twig',
			array(
				'quote' => $quote,
				'shareNotesCollection' => $shareNotesCollection,
				'shareCollection' => $shareCollection,
				'resourceCollection' => $resourceCollection,
				'final' => $final,
				'commission' => $commission,
				'profit' => $profit,
				'status' => $status,
				'taxes' => $taxes
			)
		);
	}

	private function quoteCalculateCost($quote){

		$cost = 0;
		$profit = 0;
		$commission = 0;
		$taxes = 0;

		foreach ($quote->getResourceQuoteCollection() as $resourceQuote) {
			$cost = $cost + ($resourceQuote->getValue() * $resourceQuote->getAmount());
		}

		if ( $quote->getProfit()) {
			$profit = $quote->getProfit() / 100;
		}	

		if ( $quote->getCommission()) {
			$commission = $quote->getCommission() / 100;
		}	

		if ( $quote->getTaxes()) {
			$taxes = $quote->getTaxes() / 100;
		}

		$final = $cost + 1;
		$finalProfit = (($final - $cost) / $final); //calculo de quantos % de lucro
		while($finalProfit < $profit) {
			$tempCost = $cost + ($final * $commission) + ($final * $taxes);
			$final++;
			if ($final < $tempCost) {	
				continue;
			}
			$finalProfit = (($final - $tempCost) / $final);
		}

		return array( 
			'final' => $final, 
			'commission' => $commission, 
			'profit' => $profit, 
			'taxes' => $taxes
		);
	}


	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$quoteService = new QuoteService();
		$quoteService->setEm($app['orm.em']);
		$quote = $quoteService->save($data);
		return $app->redirect('/quote/detail/' . $quote->getId());
	}

	public function delete(Request $request, Application $app, $quoteId)
	{	
		$em = $app['orm.em'];
		$quote = $em->getRepository('Orcamentos\Model\Quote')->find($quoteId);
		$projectId = $quote->getProject()->getId();
		$em->remove($quote);
		$em->flush();

		return $app->redirect($_SERVER['HTTP_REFERER']);
	}

	public function preview(Request $request, Application $app, $quoteId)
	{	
		if ( !isset($quoteId) ) {
			throw new Exception("Parâmetros inválidos", 1);
		}
		
		$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
		
		$d = new DateTime($quote->getCreated());
		$fmt = datefmt_create( "pt_BR" ,\IntlDateFormatter::LONG, \IntlDateFormatter::NONE,
		    'America/Sao_Paulo', \IntlDateFormatter::GREGORIAN  );

		$city = '';

		if($quote->getProject()->getCompany()->getCity()){
			$city = $quote->getProject()->getCompany()->getCity().', ';
		}
		
		$createdSignature = $city . datefmt_format( $fmt , $d);

		return $app['twig']->render('share/detail.twig',
			array(
				'quote' => $quote,
				'createdSignature' => $createdSignature
			)
		);
	}

	public function duplicate(Request $request, Application $app, $quoteId)
	{	
		$quoteId = $request->get('quoteId');

		if ( !isset($quoteId) ) {
			throw new Exception("Invalid Parameters", 1);
		}
		
		$data['quoteId'] = $quoteId;
    	$data = json_encode($data);
		$quoteService = new QuoteService();
		$quoteService->setEm($app['orm.em']);
		$duplicate = $quoteService->duplicate($data);

		return $app->redirect($_SERVER['HTTP_REFERER']);
	}

}