<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
	public function index(Request $request, Application $app)
	{
		$usersObjs = $app['orm.em']->getRepository('Orcamentos\Model\User')->findAll();
		return $app['twig']->render('user/index.twig', array( 
			'users' => $usersObjs
		));
	}	

	// public function edit(Request $request, Application $app, $clientId)
	// {	
	// 	$client = null;
	// 	if ( isset($clientId) ) {
	// 		$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
	// 	}

	// 	return $app['twig']->render('client/edit.twig', 
	// 		array(
	// 			'client' => $client
	// 		)
	// 	);
	// }

	// // Funcao usada para criar o cliente, via post
	// public function create(Request $request, Application $app)
	// {	
	// 	$data = $request->request->all();
	// 	$logotype = $request->files->get('logotype');

	// 	// Pegar da session
	// 	$data['companyId'] = 1;

 //    	$data = json_encode($data);
	// 	$clientService = new ClientService();
	// 	$client = $clientService->save($data, $logotype, $app['orm.em']);

	// 	return $app->redirect('/client/detail/' . $client->getId());
	// }

	// public function detail(Request $request, Application $app, $clientId )
	// {
	// 	$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
	// 	return $app['twig']->render('client/detail.twig', array( 
	// 		'client' => $client
	// 	));
	// }
}