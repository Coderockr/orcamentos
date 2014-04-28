<?php
require_once __DIR__.'/bootstrap.php';

use Silex\Application,
    Silex\Provider\DoctrineServiceProvider,
    Symfony\Component\HttpFoundation\Request,
    Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

$app = new Application();

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
    
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

// where does the user want to go?
$app->before(function (Request $request) use ($app) {
    $requestUri = $request->getRequestUri();
    if ( $requestUri !== '/'  
        && $requestUri !== '/logout' 
        && $requestUri !== '/login' 
        && stripos($requestUri, 'share') == false 
        && $app['session']->get('email') == null ) {
        return $app->redirect('/');
    }
    if( ( stripos($requestUri, 'client') == true
        || stripos($requestUri, 'project')   == true
        || stripos($requestUri, 'quote')  == true
        || stripos($requestUri, 'lead') == true ) && $app['session']->get('isAdmin') == false ) {
        return $app->redirect('/');
    }
});
    
// Index Controller / Dashboard
$app->get('/', 'Orcamentos\Controller\IndexController::index');

// Client controller  
$app->get('/client/edit/{clientId}', 'Orcamentos\Controller\ClientController::edit')->value( "clientId", null );
$app->get('/client/detail/{clientId}', 'Orcamentos\Controller\ClientController::detail');
$app->post('/client/create', 'Orcamentos\Controller\ClientController::create');
$app->get('/client/delete/{clientId}', 'Orcamentos\Controller\ClientController::delete');

$app->get('/client/{page}', 'Orcamentos\Controller\ClientController::index')->value('page', 1);

// User controller  
$app->get('/user/edit/{userId}', 'Orcamentos\Controller\UserController::edit')->value( "userId", null );
$app->get('/user/detail/{userId}', 'Orcamentos\Controller\UserController::detail');
$app->post('/user/create', 'Orcamentos\Controller\UserController::create');
$app->get('/user/delete/{userId}', 'Orcamentos\Controller\UserController::delete');
$app->get('/user/{page}', 'Orcamentos\Controller\UserController::index')->value('page', 1);

// Project Controller
$app->get('/project/edit/{projectId}', 'Orcamentos\Controller\ProjectController::edit')->value( "projectId", null );
$app->get('/project/detail/{projectId}', 'Orcamentos\Controller\ProjectController::detail');
$app->post('/project/create', 'Orcamentos\Controller\ProjectController::create');
$app->get('/project/delete/{projectId}', 'Orcamentos\Controller\ProjectController::delete');
$app->get('/project/{page}', 'Orcamentos\Controller\ProjectController::index')->value('page', 1);
$app->post('/project/comment', 'Orcamentos\Controller\ProjectController::comment');

//Company Controller
$app->get('/company', 'Orcamentos\Controller\CompanyController::edit');
$app->post('/company/create', 'Orcamentos\Controller\CompanyController::create');

//quote Controller

$app->get('/quote/new/{projectId}', 'Orcamentos\Controller\QuoteController::edit');
$app->get('/quote/edit/{quoteId}', 'Orcamentos\Controller\QuoteController::edit');
$app->get('/quote/detail/{quoteId}', 'Orcamentos\Controller\QuoteController::detail');
$app->post('/quote/create', 'Orcamentos\Controller\QuoteController::create');

// Share Controller
$app->get('/share/delete/{shareId}', 'Orcamentos\Controller\ShareController::delete');
$app->post('/share/create', 'Orcamentos\Controller\ShareController::create');
$app->post('/share/comment', 'Orcamentos\Controller\ShareController::comment');
$app->get('/share/{shareId}', 'Orcamentos\Controller\ShareController::detail');

//Resource Controller
$app->post('/resource/create', 'Orcamentos\Controller\ResourceController::create');
$app->get('/resource/delete/{resourceId}', 'Orcamentos\Controller\ResourceController::delete');
$app->get('/resource/load', 'Orcamentos\Controller\ResourceController::load');

//Admin Controller
$app->post('/login', 'Orcamentos\Controller\AdminController::login');
$app->get('/logout', 'Orcamentos\Controller\AdminController::logout');

//getting the EntityManager
$app->register(new DoctrineServiceProvider, array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => '',
        'dbname' => 'orcamentos'
    )
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => '/tmp/' . getenv('APPLICATION_ENV'),
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
    'orm.auto_generate_proxies' => true
));