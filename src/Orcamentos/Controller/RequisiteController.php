<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/11/15
 * Time: 01:37
 */
namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Requisite as RequisiteService;
use DateTime;
use Orcamentos\Controller\BaseController;
use Exception;

use \IntlDateFormatter;

class RequisiteController extends BaseController
{
    public function mount($controller)
    {
        $controller->post('/create', array($this, 'create'));
        $controller->get('/get/{requisiteId}', array($this, 'get'));
        $controller->get('/delete/{requisiteId}', array($this, 'delete'));
    }

    public function edit(Request $request, Application $app)
    {
        $projectId = $request->get('projectId');
        $requisite = $request->get('requisiteId');

        if (!isset($projectId) && !isset($requisiteId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $requisite = null;
        $project = null;

        return $app['twig']->render(
            'requisite/edit.twig',
            array(
                'requirement' => $requisite,
                'project' => $project,
            )
        );
    }

    public function create(Request $request, Application $app)
    {
        $data = $request->request->all();
        $data['companyId'] = $app['session']->get('companyId');
        $data = json_encode($data);
        $requisiteService = new RequisiteService();
        $requisiteService->setEm($app['orm.em']);
        $requisite = $requisiteService->save($data);

        $result = array();
        $result['name'] = $requisite->getName();
        $result['description'] = $requisite->getDescription();
        $result['expectedAmount'] = $requisite->getExpectedAmount();
        $result['spentAmount'] = $requisite->getSpentAmount();
        $result['project'] = $requisite->getProject();
        $result['id'] = $requisite->getId();

        return json_encode($result);

    }

    public function get(Request $request, Application $app, $requisiteId)
    {
        $data = json_encode($requisiteId);
        $requisiteService = new RequisiteService();
        $requisiteService->setEm($app['orm.em']);
        $requisite = $requisiteService->get($data);

        return json_encode($requisite);
    }

    public function delete(Request $request, Application $app, $requisiteId)
    {
        $em = $app['orm.em'];
        $requisite = $em->getRepository('Orcamentos\Model\Requisite')->find($requisiteId);
        $projectId = $requisite->getProject()->getId();

        $em->remove($requisite);
        $em->flush();
        return $app->redirect('/project/detail/' . $projectId);
    }
}
