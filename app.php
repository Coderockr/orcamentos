<?php

require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app['debug'] = true;

$config = require_once __DIR__ . '/config/config.php';

if (!$config) {
    throw new \Exception("Error Processing Config", 1);
}

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = $config['swiftmailer.options'];

$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = $app['twig']->render('error404.twig', array('code'=>$code, 'message' => $e->getMessage()));
            break;
        default:
            $message = $e->getMessage() . ' no arquivo ' . $e->getFile() . ', na linha: '. $e->getLine();
            break;
    }
    return new Response($message, $code);
});

$app['sortCreated'] = $app->protect(function ($a, $b) {
    if ($a->getCreated() == $b->getCreated()) {
        return 0;
    }
    return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
});

$app->before(function (Request $request) use ($app) {

    $target = $request->get('_controller');
    if (is_object($target)) {
        return;
    }

    if (!is_array($target)) {
        $target = explode(':', $target, 2);
    }
    list($controller, $action) = $target;
    $valid = forward_static_call_array(array($controller, 'isPublic'), array($action));

    // If the url is public, the user can access =)
    if ($valid) {
        return;
    }

    // Check if the user is logged in
    if (null === $app['session']->get('email')) {
        return $app->redirect('/');
    }

    // If the user is an admin, he can access anything
    if ($app['session']->get('isAdmin')) {
        return;
    }

    // Check if the action is accessible by guest users
    $validGuest = forward_static_call_array(array($controller, 'isGuest'), array($action));

    //Common users only access the /project controller
    if (!$validGuest) {
        return $app->redirect('/project');
    }

});

$app->mount('/', new Orcamentos\Controller\IndexController);
$app->mount('/status', new Orcamentos\Controller\StatusController);
$app->mount('/client', new Orcamentos\Controller\ClientController);
$app->mount('/company', new Orcamentos\Controller\CompanyController);
$app->mount('/project', new Orcamentos\Controller\ProjectController);
$app->mount('/quote', new Orcamentos\Controller\QuoteController);
$app->mount('/resource', new Orcamentos\Controller\ResourceController);
$app->mount('/share', new Orcamentos\Controller\ShareController);
$app->mount('/user', new Orcamentos\Controller\UserController);
$app->mount('/requisite', new Orcamentos\Controller\RequisiteController);

//getting the EntityManager
$app->register(new DoctrineServiceProvider, array(
    'db.options' => $config['db.options']
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => sys_get_temp_dir() . '/' . md5(__DIR__ . getenv('APPLICATION_ENV')),
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'Orcamentos\Model',
                'path' => __DIR__ . '/src'
            )
        )
    ),
    'orm.proxies_namespace' => 'EntityProxy',
    'orm.auto_generate_proxies' => true,
    'orm.default_cache' => $config['db.options']['cache']
));
