<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

		if ( isset($projectId) ) {
			$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
			$version = count($project->getQuoteCollection()) + 1;
		} else {
			$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
			$project = $quote->getProject();
			$version = $quote->getVersion();
		}
		
		$resourceCollection = $app['orm.em']->getRepository('Orcamentos\Model\ResourceQuote')->findBy( array( 'quote' => $quote ) );
		
		// $equipmentResources = array();
		// $serviceResources = array();
		// $humanResources = array();

		// if(count($resourceCollection) >0 ){
		// 	foreach ($resourceCollection as $resourceQuote) {
		// 		$type = $resourceQuote->getResource()->getType();
		// 		switch (get_class($type)) {

		// 		 	case 'Orcamentos\Model\EquipmentType':
		// 		 		$equipmentResources[] = $resourceQuote;
		// 		 		break;

		// 		 	case 'Orcamentos\Model\ServiceType':
		// 		 		$serviceResources[] = $resourceQuote;
		// 		 		break;

		// 		 	case 'Orcamentos\Model\HumanType':
		// 		 		$humanResources[] = $resourceQuote;
		// 		 		break;
		// 		};
		// 	}
		// }
		
		return $app['twig']->render('quote/edit.twig',
			array(
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
}