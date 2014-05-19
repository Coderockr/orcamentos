<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Resource as ResourceService;


class ResourceController
{
	/**
	* 	Funcao usada para criar o resource, via post
	*/
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);

		$resourceService = new ResourceService();
		$resourceService->setEm($app['orm.em']);
		$resource = $resourceService->save($data);

		$result = array();
		$result['name'] = $resource->getName();
		$result['cost'] = $resource->getCost();
		$typename=$resource->getType()->getName();
		$result['equipmentLife'] = $resource->getEquipmentLife();
		$result['type']['name'] = $typename;
		$result['id'] = $resource->getId();

		return json_encode($result);
	}

	public function load(Request $request, Application $app)
	{	
		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$resourceService = new ResourceService();
		$resourceService->setEm($app['orm.em']);
		$resources = $resourceService->load($data);
		return json_encode($resources);
	}

	public function delete(Request $request, Application $app, $resourceId)
	{	
		$em = $app['orm.em'];
		$resource = $em->getRepository('Orcamentos\Model\Resource')->find($resourceId);
		
		if (count($resource->getResourceQuoteCollection()) > 0) {
			$app['session']->getFlashBag()->add('message', 'Recurso já está ligado a algum orçamento');
			return $app->redirect('/company');	
		}

		$em->remove($resource);
		$em->flush();
		return $app->redirect('/company');
	}
}