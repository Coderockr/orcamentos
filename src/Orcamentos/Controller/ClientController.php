<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Client as ClientService;

class ClientController
{
	public function index(Request $request, Application $app)
	{
		$clientObjs = $app['orm.em']->getRepository('Orcamentos\Model\Client')->findAll();
		$clients = array();
		foreach ($clientObjs as $i => $client) {
			$clients[$i]['id'] = $client->getId();
			$clients[$i]['name'] = $client->getName();
			$clients[$i]['logotype'] = $client->getLogotype();
			$projectCollection = $client->getProjectCollection();
			$clients[$i]['numProjects'] = count($projectCollection);
			foreach ($projectCollection as  $project) {
				$clients[$i]['numQuotes'] += count($project->getQuoteCollection());
			}
		}
		return $app['twig']->render('client/index.twig', array( 
			'clients' => $clients
		));
	}	

	public function edit(Request $request, Application $app, $clientId)
	{	
		if ( isset($clientId) ) {
			$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
		}

		return $app['twig']->render('client/edit.twig', 
			array(
				'client' => $client
			)
		);
	}

	// Funcao usada para criar o cliente, via post
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		$logotype = $request->files->get('logotype');
		
		// Pegar da session
		$data['companyId'] = 1;

    	$data = json_encode($data);
		$clientService = new ClientService();
		$client = $clientService->newClient($data, $logotype, $app['orm.em']);

		return $app->redirect('/client/detail/' . $client->getId());
	}

	public function detail(Request $request, Application $app, $clientId )
	{
		$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
		return $app['twig']->render('client/detail.twig', array( 
			'client' => $client
		));
	}
}