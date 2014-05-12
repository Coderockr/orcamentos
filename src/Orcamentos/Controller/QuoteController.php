<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Quote as QuoteService;

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

		$cost = 0;
		$profit = 0;
		$commission = 0;
		$taxes = 0;

		foreach ($resourceCollection as $resourceQuote) {
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

		$shareCollection = $quote->getShareCollection();
		
		$shareNotesCollection = array();

		foreach ($shareCollection as $sc) {
			$notes = $sc->getShareNotesCollection();
			foreach ($notes as $note) {
				$shareNotesCollection[] = $note;
			}
		}

		if( count($shareNotesCollection) > 0 ) {
			usort($shareNotesCollection, function ($a, $b)
			{
			    if ($a->getCreated() == $b->getCreated()) {
			        return 0;
			    }
			    return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
			});
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
				'taxes' => $taxes
			)
		);
	}

	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$quoteService = new QuoteService();
		$quote = $quoteService->save($data, $app['orm.em']);
		return $app->redirect('/quote/detail/' . $quote->getId());
	}

	public function preview(Request $request, Application $app, $quoteId)
	{	
		if ( !isset($quoteId) ) {
			throw new Exception("Parâmetros inválidos", 1);
		}
		
		$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
		
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		
		$monthName = null;

		switch ($month) {
			case '01':
				$monthName = 'Janeiro';
				break;

			case '02':
				$monthName = 'Fevereiro';
				break;

			case '03':
				$monthName = 'Março';
				break;

			case '04':
				$monthName = 'Abril';
				break;

			case '05':
				$monthName = 'Maio';
				break;

			case '06':
				$monthName = 'Junho';
				break;

			case '07':
				$monthName = 'Julho';
				break;

			case '08':
				$monthName = 'Agosto';
				break;

			case '09':
				$monthName = 'Setembro';
				break;

			case '10':
				$monthName = 'Outubro';
				break;

			case '11':
				$monthName = 'Novembro';
				break;

			case '12':
				$monthName = 'Dezembro';
				break;
		}

		$createdSignature = 'Joinville, ' . $day . ' de ' . $monthName . ' de ' . $year . '.';

		return $app['twig']->render('share/detail.twig',
			array(
				'quote' => $quote,
				'createdSignature' => $createdSignature
			)
		);
	}
}