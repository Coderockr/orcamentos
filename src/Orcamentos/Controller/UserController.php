<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\User as UserService;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;

class UserController extends BaseController
{

    public function mount($controller)
    {
        $controller->get('/edit/{userId}', array($this, 'edit'))->value("userId", null);
        $controller->get('/detail/{userId}', array($this, 'detail'));
        $controller->post('/create', array($this, 'create'));
        $controller->get('/delete/{userId}', array($this, 'delete'));
        $controller->get('/{page}', array($this, 'index'))->value('page', 1);
    }

    public function index(Request $request, Application $app, $page)
    {
        $company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get('companyId'));
        $users = $company->getUserCollection();

        $adapter = new DoctrineCollectionAdapter($users);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(9);
        $pagerfanta->setCurrentPage($page);
        $view = new TwitterBootstrap3View();
        $routeGenerator = function ($page) use ($app) {
            return '/user/'.$page;
        };

        $htmlPagination = $view->render($pagerfanta, $routeGenerator, array());
        return $app['twig']->render('user/index.twig', array(
            'htmlPagination' => $htmlPagination,
            'pagerfanta' => $pagerfanta,
            'active_page' => 'user'
        ));
    }

    public function edit(Request $request, Application $app, $userId)
    {
        $user = null;
        if (isset($userId)) {
            $user = $app['orm.em']->getRepository('Orcamentos\Model\User')->find($userId);
        }
        return $app['twig']->render(
            'user/edit.twig',
            array(
                'user' => $user,
                'active_page' => 'user'
            )
        );
    }

    // Funcao usada para criar o cliente, via post
    public function create(Request $request, Application $app)
    {
        $data = $request->request->all();
        $data['companyId'] = $app['session']->get('companyId');

        $data = json_encode($data);
        $userService = new UserService();
        $userService->setEm($app['orm.em']);
        $user = $userService->save($data);
        return $app->redirect('/user');
    }

    public function detail(Request $request, Application $app, $userId)
    {
        $user = $app['orm.em']->getRepository('Orcamentos\Model\User')->find($userId);
        return $app['twig']->render('user/detail.twig', array(
            'user' => $user,
            'active_page' => 'user'
        ));
    }

    public function delete(Request $request, Application $app, $userId)
    {
        $em = $app['orm.em'];
        $users = $em->getRepository('Orcamentos\Model\User')->find($userId);
        $em->remove($users);
        $em->flush();

        return $app->redirect('/user');
    }
}
