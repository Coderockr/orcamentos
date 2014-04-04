<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\User as UserService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\Adapter\ArrayAdapter;;

class UserController
{
	public function index(Request $request, Application $app, $page)
	{
		$users = $app['orm.em']->getRepository('Orcamentos\Model\User')->findAll();
		
		$adapter = new ArrayAdapter($users);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setCurrentPage($page);
		$view = new TwitterBootstrapView();
		$routeGenerator = function($page) use ($app) {
	        return '/user/'.$page;
	    };
		$htmlPagination = $view->render( $pagerfanta, $routeGenerator, array());
		return $app['twig']->render('user/index.twig', array( 
			'htmlPagination' => $htmlPagination,
			'pagerfanta' => $pagerfanta
		));
	}	

	public function edit(Request $request, Application $app, $userId)
	{	
		$user = null;
		if ( isset($userId) ) {
			$user = $app['orm.em']->getRepository('Orcamentos\Model\User')->find($userId);
		}

		return $app['twig']->render('user/edit.twig', 
			array(
				'user' => $user
			)
		);
	}

	// Funcao usada para criar o cliente, via post
	public function create(Request $request, Application $app)
	{	
		$data = $request->request->all();

		// Pegar da session
		$data['companyId'] = 1;

    	$data = json_encode($data);
		$userService = new UserService();
		$user = $userService->save($data, $app['orm.em']);
		return $app->redirect('/user');
	}

	public function detail(Request $request, Application $app, $userId )
	{
		$user = $app['orm.em']->getRepository('Orcamentos\Model\User')->find($userId);
		return $app['twig']->render('user/detail.twig', array( 
			'user' => $user
		));
	}
}