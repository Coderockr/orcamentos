<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController
{
	public function index(Request $request, Application $app)
	{
		$em = $app['orm.em'];
		$projects = $em->getRepository('Orcamentos\Model\Project')->findAll();
		return $app['twig']->render('project/index.twig', array( 'projectCollection' => $projects ));
	}

	public function edit(Request $request, Application $app)
	{
		return $app['twig']->render('project/edit.twig', array());
	}
	
}