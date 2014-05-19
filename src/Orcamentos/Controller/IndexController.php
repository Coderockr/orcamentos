<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Dashboard as DashboardService;

class IndexController
{
	public function index(Request $request, Application $app)
	{
		if ( !$app['session']->get('email')){
			return $app['twig']->render('login.twig', array());
		} 
		$companyId = $app['session']->get('companyId');
		$data = array('companyId' => $companyId);
		$dashboardService = new DashboardService();
		$dashboardService->setEm($app['orm.em']);
		$result = $dashboardService->getData(json_encode($data));

		return $app['twig']->render('index/index.twig', array(
			'result' => $result,
			'active_page' => 'panel' 
		));
	}
}