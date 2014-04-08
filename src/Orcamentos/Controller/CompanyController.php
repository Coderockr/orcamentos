<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController
{
	public function index(Request $request, Application $app)
	{
		return $app['twig']->render('company/index.twig', array( 'active_page' => ''));
	}
}