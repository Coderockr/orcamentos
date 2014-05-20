<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Status as StatusService;

class StatusController
{
	function sort ($a, $b)
    {
        if ($a->getCreated() == $b->getCreated()) {
            return 0;
        }
        return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
    }

	public function index(Request $request, Application $app)
	{
		$em = $app['orm.em'];
		$companyId = $app['session']->get('companyId');
		$data = array('companyId' => $companyId);
		$statusService = new StatusService();
		$statusService->setEm($app['orm.em']);
		$result = $statusService->get(json_encode($data));

		$awaiting = array_shift($result);
		$aproved = array_shift($result);
		$nonAproved = array_shift($result);

		usort($awaiting, "sort" );
		usort($aproved, "sort" );
		usort($nonAproved, "sort" );
        
		return $app['twig']->render('status/index.twig', array(
			'awaiting' => $awaiting,
			'aproved' => $aproved,
			'nonAproved' => $nonAproved,
			'active_page' => 'status' 
		));
	}
}