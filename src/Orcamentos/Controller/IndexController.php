<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
	public function index(Request $request, Application $app)
	{
		if ( !$app['session']->get('email') ) {
			return $app['twig']->render('login.twig', array( 'active_page' => ''));
		}

		return $app['twig']->render('index/index.twig', array( 'active_page' => 'panel' ));
	}
}