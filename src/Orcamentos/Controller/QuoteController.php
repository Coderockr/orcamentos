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

		if ( isset($projectId) ) {
			$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
			$version = count($project->getQuoteCollection()) + 1;
		} else {
			$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
			$project = $quote->getProject();
			$version = $quote->getVersion();

			$quoteResources = $quote->getResourceQuoteCollection();
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
		$quote = null;
		$resourceCollection = null;
		if ( isset($quoteId) ) {
			$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
		}

		if ( isset($quote)){
			$resourceCollection = $quote->getResourceQuoteCollection();
		}

		return $app['twig']->render('quote/detail.twig',
			array(
				'quote' => $quote,
				'resourceCollection' => $resourceCollection
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
}