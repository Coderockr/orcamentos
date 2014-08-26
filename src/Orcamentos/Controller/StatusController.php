<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Status as StatusService;

class StatusController extends BaseController
{

    public function mount($controller)
    {
        $controller->get('', array($this, 'getIndex'));
    }

    public function getIndex(Request $request, Application $app)
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

        $sortCreated = $app['sortCreated'];

        usort($awaiting, $app['sortCreated'] );
        usort($aproved, $app['sortCreated'] );
        usort($nonAproved, $app['sortCreated'] );

        return $app['twig']->render('status/index.twig', array(
            'awaiting' => $awaiting,
            'aproved' => $aproved,
            'nonAproved' => $nonAproved,
            'active_page' => 'status'
        ));
    }
}
