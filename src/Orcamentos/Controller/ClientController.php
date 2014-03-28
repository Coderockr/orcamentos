<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController
{
	public function index(Request $request, Application $app)
	{
		return $app['twig']->render('client/index.twig', array());
	}	

	public function create(Request $request, Application $app)
	{
		return $app['twig']->render('client/create.twig', array());
	}

	public function detail(Request $request, Application $app)
	{
		return $app['twig']->render('client/detail.twig', array());
	}
}