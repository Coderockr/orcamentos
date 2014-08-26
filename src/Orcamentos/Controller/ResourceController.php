<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Resource as ResourceService;

class ResourceController extends BaseController
{

    public function mount($controller)
    {
        $controller->get('/', array($this, 'index'));
        $controller->post('/create', array($this, 'create'));
        $controller->get('/delete/{resourceId}', array($this, 'delete'));
        $controller->get('/get', array($this, 'get'));
    }

    public function create(Request $request, Application $app)
    {
        $data = $request->request->all();
        $data['companyId'] = $app['session']->get('companyId');
        $data = json_encode($data);

        $resourceService = new ResourceService();
        $resourceService->setEm($app['orm.em']);
        $resource = $resourceService->save($data);

        $result = array();
        $result['name'] = $resource->getName();
        $result['cost'] = $resource->getCost();
        $typename=$resource->getType()->getName();
        $result['equipmentLife'] = $resource->getEquipmentLife();
        $result['type']['name'] = $typename;
        $result['id'] = $resource->getId();

        return json_encode($result);
    }

    public function get(Request $request, Application $app)
    {
        $data['companyId'] = $app['session']->get('companyId');
        $data = json_encode($data);
        $resourceService = new ResourceService();
        $resourceService->setEm($app['orm.em']);
        $resources = $resourceService->get($data);
        return json_encode($resources);
    }

    public function delete(Request $request, Application $app, $resourceId)
    {
        $em = $app['orm.em'];
        $resource = $em->getRepository('Orcamentos\Model\Resource')->find($resourceId);

        if (count($resource->getResourceQuoteCollection()) > 0) {
            $app['session']->getFlashBag()->add('message', 'Recurso já está ligado a algum orçamento');
            return $app->redirect('/company');
        }

        $em->remove($resource);
        $em->flush();
        return $app->redirect('/company');
    }
}
