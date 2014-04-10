<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Resource as ResourceService;


class ResourceController
{
	// Funcao usada para criar o cliente, via post
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		
		$data['companyId']= $app['session']->get('companyId');

    	$data = json_encode($data);

		$resourceService = new ResourceService();
		$resource = $resourceService->save($data, $app['orm.em']);

		return $app->redirect('/company');
	}

	public function delete(Request $request, Application $app, $resourceId)
	{	
		$em = $app['orm.em'];
		$resource = $em->getRepository('Orcamentos\Model\Resource')->find($projectId);
		$em->remove($project);
		$em->flush();

		return $app->redirect('/company');
	}
}