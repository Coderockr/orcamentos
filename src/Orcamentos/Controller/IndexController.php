<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Dashboard as DashboardService;
use Zend\Crypt\Password\Bcrypt;

class IndexController extends BaseController
{

    public static function getPublicActions()
    {
        return array('getIndex', 'getLogout', 'postLogin');
    }

    public function mount($controller)
    {
        $controller->get('/', array($this, 'getIndex'));
        $controller->post('/login', array($this, 'postLogin'));
        $controller->get('/logout', array($this, 'getLogout'));
    }

    public function getIndex(Request $request, Application $app)
    {

        if ($app['session']->get('email') == null) {
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

    public function postLogin(Request $request, Application $app)
    {
        $data = $request->request->all();

        if (!isset($data['email']) || !isset($data['password'])) {
            $app['session']->getFlashBag()->add('message', 'Email e/ou senha inválidos!');
            return $app->redirect('/');
        }

        $user = $app['orm.em']->getRepository('Orcamentos\Model\User')->findOneBy(array( 'email' => $data['email'] ));

        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'Usuário inválido!');
            return $app->redirect('/');
        }

        $bcrypt = new Bcrypt;
        $valid = $bcrypt->verify($data['password'], $user->getPassword());

        if (!$valid) {
            $app['session']->getFlashBag()->add('message', 'Email e/ou senha inválidos!');
            return $app->redirect('/');
        }

        $app['session']->set('email', $data['email']);
        $app['session']->set('isAdmin', $user->getAdmin());
        $app['session']->set('companyId', $user->getCompany()->getId());
        $app['session']->set('companyLogotype', $user->getCompany()->getLogotype());
        $app['session']->set('companyName', $user->getCompany()->getName());

        if ($user->getAdmin()) {
            return $app->redirect('/');
        }

        return $app->redirect('/project');
    }

    public function getLogout(Request $request, Application $app)
    {
        $app['session']->clear();
        return $app->redirect('/');
    }
}
