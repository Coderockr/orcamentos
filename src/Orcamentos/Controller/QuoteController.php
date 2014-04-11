<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuoteController
{
	public function edit(Request $request, Application $app, $quoteId)
	{	
		$quote = null;
		if ( isset($quoteId) ) {
			$quote = $app['orm.em']->getRepository('Orcamentos\Model\Quote')->find($quoteId);
		}

		$resourceCollection = $app['orm.em']->getRepository('Orcamentos\Model\ResourceQuote')->findBy( array( 'quote' => $quote ) );
		$equipmentResources = array();
		$serviceResources = array();
		$humanResources = array();

		if(count($resourceCollection) >0 ){
			foreach ($resourceCollection as $resource) {
				$type = $resource->getResource()->getType();
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
		}
		
		return $app['twig']->render('quote/edit.twig',
			array(
				'quote' => $quote,
				'equipmentResources' => $equipmentResources,
				'serviceResources' => $serviceResources,
				'humanResources' => $humanResources
			)
		);
	}
}