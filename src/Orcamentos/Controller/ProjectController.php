<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Project as ProjectService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;

class ProjectController
{
	public function index(Request $request, Application $app, $page)
	{
		$em = $app['orm.em'];
		
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get('companyId'));
		$projects = $company->getProjectCollection();

		$adapter = new DoctrineCollectionAdapter($projects);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setCurrentPage($page);
		$view = new TwitterBootstrapView();
		$routeGenerator = function($page) use ($app) {
	        return '/user/'.$page;
	    };
		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());
		return $app['twig']->render('project/index.twig', array( 
			'htmlPagination' => $htmlPagination,
			'pagerfanta' => $pagerfanta,
			'active_page' => 'project'
		));
	}

	public function edit(Request $request, Application $app, $projectId)
	{
		$em = $app['orm.em'];
		$project = null;
		if ( isset($projectId) ) {
			$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
		}
		$clients = $em->getRepository('Orcamentos\Model\Client')->findAll();
		return $app['twig']->render('project/edit.twig', 
			array(
				'clients' => $clients,
				'project' => $project,
				'active_page' => 'project'
			)
		);
	}

	// Funcao usada para criar o cliente, via post
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();
		
		$data['companyId']= $app['session']->get('companyId');

    	$data = json_encode($data);

		$projectService = new ProjectService();
		$project = $projectService->save($data, $app['orm.em']);

		return $app->redirect('/project');
	}

	public function detail(Request $request, Application $app, $projectId )
	{
		$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
		return $app['twig']->render('project/detail.twig', array( 
			'project' => $project,
			'active_page' => 'project'
		));
	}

	public function delete(Request $request, Application $app, $projectId)
	{	
		$em = $app['orm.em'];
		$project = $em->getRepository('Orcamentos\Model\Project')->find($projectId);
		$em->remove($project);
		$em->flush();

		return $app->redirect('/project');
	}

	public function comment(Request $request, Application $app)
	{
		$data = $request->request->all();

    	$data = json_encode($data);
		$projectService = new ProjectService();
		$note = $projectService->comment($data, $app['orm.em']);

		return json_encode($note);
	}
}