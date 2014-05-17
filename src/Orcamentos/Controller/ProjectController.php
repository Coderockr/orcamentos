<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Project as ProjectService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class ProjectController
{
	public function index(Request $request, Application $app, $page)
	{
		$em = $app['orm.em'];
		
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get('companyId'));
		$projects = $company->getProjectCollection();

		$adapter = new DoctrineCollectionAdapter($projects);
		$pagerfanta = new Pagerfanta($adapter);

		$view = new TwitterBootstrap3View();
		$routeGenerator = function($page) use ($app) {
	        return '/project/'.$page;
	    };
		$pagerfanta->setCurrentPage($page);

		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());
		return $app['twig']->render('project/index.twig', array( 
			'htmlPagination' => $htmlPagination,
			'pagerfanta' => $pagerfanta,
			'active_page' => 'project'
		));
	}

	public function search(Request $request, Application $app, $page)
	{
		$data = $request->query->all();

		if ($data['query'] == ''){
			return $app->redirect('/project');
		}

		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$projectService = new ProjectService();
		$projectService->setEm($app['orm.em']);
		$query = $projectService->search($data);

		$adapter = new DoctrineORMAdapter($query);
		$pagerfanta = new Pagerfanta($adapter);

		$view = new TwitterBootstrap3View();
		$routeGenerator = function($page) use ($app) {
	        return '/project/'.$page;
	    };

		$pagerfanta->setCurrentPage($page);
		
		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());

		return $app['twig']->render('project/index.twig', 
			array(
				'htmlPagination' => $htmlPagination,
				'pagerfanta' => $pagerfanta,
				'active_page' => 'project'
			)
		);
	}

	public function edit(Request $request, Application $app)
	{
		$em = $app['orm.em'];
		$projectId = $request->get('projectId');
		$clientId = $request->get('clientId');

		$client = null;
		$clients = null;
		$project = null;

		$companyId = $app['session']->get('companyId');
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($companyId);

		if ( isset($clientId) ) {
			$client = $app['orm.em']->getRepository('Orcamentos\Model\Client')->find($clientId);
		} 

		if ( isset($projectId) ){
			$project = $em->getRepository('Orcamentos\Model\Project')->find($projectId);
			$client = $project->getClient();
		}

		if(!$client){
			$clients = $app['orm.em']->getRepository('Orcamentos\Model\Client')->findBy(array('company' => $company));
		}

		return $app['twig']->render('project/edit.twig', 
			array(
				'clients' => $clients,
				'client' => $client,
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
		$projectService->setEm($app['orm.em']);
		$project = $projectService->save($data);
		return $app->redirect('/project/detail/' . $project->getId() );
	}

	public function detail(Request $request, Application $app, $projectId )
	{
		$project = $app['orm.em']->getRepository('Orcamentos\Model\Project')->find($projectId);
		$user = $app['orm.em']->getRepository('Orcamentos\Model\User')->findOneBy(array('email' => $app['session']->get('email')));

		$projectNotesCollection = $project->getPrivateNotesCollection();
		
		return $app['twig']->render('project/detail.twig', array( 
			'project' => $project,
			'userId' => $user->getId(),
			'projectNotesCollection' => $projectNotesCollection,
			'active_page' => 'project'
		));
	}

	public function delete(Request $request, Application $app, $projectId)
	{	
		$em = $app['orm.em'];
		$project = $em->getRepository('Orcamentos\Model\Project')->find($projectId);
		$em->remove($project);
		$em->flush();

		return $app->redirect($_SERVER['HTTP_REFERER']);
	}

	public function comment(Request $request, Application $app)
	{
		$data = $request->request->all();
    	$data = json_encode($data);
		$projectService = new ProjectService();
		$projectService->setEm($app['orm.em']);
		$note = $projectService->comment($data);
		$result = array(
			'email'=> $note->getUser()->getEmail(),
			'name' => $note->getUser()->getName(),
			'note' => $note->getNote(),
			'id' => $note->getId()
		);
		return json_encode($result);
	}

	public function removeComment(Request $request, Application $app, $noteId )
	{
    	$data = json_encode(array('noteId' => $noteId));
		$projectService = new ProjectService();
		$projectService->setEm($app['orm.em']);
		$note = $projectService->removeComment($data);
		return $app->redirect('/project/detail/' . $note->getProject()->getId());
	}
}