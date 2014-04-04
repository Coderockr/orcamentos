<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Client as ClientService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\Adapter\ArrayAdapter;

class ClientController
{
	public function index(Request $request, Application $app, $page)
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

		$adapter = new ArrayAdapter($clients);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setCurrentPage($page);
		$view = new TwitterBootstrapView();
		$routeGenerator = function($page) use ($app) {
	        return '/user/'.$page;
	    };
		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());
		return $app['twig']->render('client/index.twig', array( 
			'htmlPagination' => $htmlPagination,
			'pagerfanta' => $pagerfanta
		));
	}	

	public function edit(Request $request, Application $app, $clientId)
	{	
		$client = null;
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
		$client = $clientService->save($data, $logotype, $app['orm.em']);

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