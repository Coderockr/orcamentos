<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Client as ClientService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class ClientController
{
	public function index(Request $request, Application $app, $page)
	{
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get("companyId"));
		$clientObjs = $company->getClientCollection();
		$clients = array();
		foreach ($clientObjs as $i => $client) {
			$clients[$i]['id'] = $client->getId();
			$clients[$i]['name'] = $client->getName();
			$clients[$i]['logotype'] = $client->getLogotype();
			$projectCollection = $client->getProjectCollection();
			$clients[$i]['numProjects'] = count($projectCollection);
			$clients[$i]['numQuotes'] = 0;
			foreach ($projectCollection as  $project) {
				$clients[$i]['numQuotes'] += count($project->getQuoteCollection());
			}
		}

		$adapter = new ArrayAdapter($clients);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(9);
		$pagerfanta->setCurrentPage($page);
		$view = new TwitterBootstrap3View();
		$routeGenerator = function($page) use ($app) {
	        return '/client/'.$page;
	    };

		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());
		return $app['twig']->render('client/index.twig', array( 
			'htmlPagination' => $htmlPagination,
			'pagerfanta' => $pagerfanta,
			'active_page' => 'client'
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
				'client' => $client,
				'active_page' => 'client'
			)
		);
	}
	
	public function search(Request $request, Application $app, $page)
	{
		$data = $request->query->all();

		if ($data['query'] == ''){
			return $app->redirect('/client');
		}

		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$clientService = new ClientService();
		$clientService->setEm($app['orm.em']);
		$query = $clientService->search($data);

		$adapter = new DoctrineORMAdapter($query);
		$pagerfanta = new Pagerfanta($adapter);

		$view = new TwitterBootstrap3View();
		$routeGenerator = function($page) use ($app) {
	        return '/client/'.$page;
	    };

		$pagerfanta->setCurrentPage($page);
		
		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());

		return $app['twig']->render('client/index.twig', 
			array(
				'htmlPagination' => $htmlPagination,
				'pagerfanta' => $pagerfanta,
				'active_page' => 'client'
			)
		);
	}
	
	// Funcao usada para criar o cliente, via post
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		$logotype = $request->files->get('logotype');

		$data['companyId']= $app['session']->get('companyId');

    	$data = json_encode($data);
		$clientService = new ClientService();
		$clientService->setEm($app['orm.em']);
		$client = $clientService->save($data, $logotype);

		return $app->redirect('/client/detail/' . $client->getId());
	}

	public function detail(Request $request, Application $app, $clientId )
	{
		$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
		return $app['twig']->render('client/detail.twig', array( 
			'client' => $client,
			'active_page' => 'client'
		));
	}

	public function delete(Request $request, Application $app, $clientId)
	{	
		$em = $app['orm.em'];
		$client = $em->getRepository('Orcamentos\Model\Client')->find($clientId);
		$em->remove($client);
		$em->flush();

		return $app->redirect('/client');
	}
}