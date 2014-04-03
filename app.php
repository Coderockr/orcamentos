<?php
require_once __DIR__.'/bootstrap.php';

use Silex\Application,
    Silex\Provider\DoctrineServiceProvider,
    Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

$app = new Application();

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

// Index Controller / Dashboard
$app->get('/', 'Orcamentos\Controller\IndexController::index');

// Client controller  
$app->get('/client', 'Orcamentos\Controller\ClientController::index');
$app->get('/client/edit/{clientId}', 'Orcamentos\Controller\ClientController::edit')->value( "clientId", null );
$app->get('/client/detail/{clientId}', 'Orcamentos\Controller\ClientController::detail');

$app->post('/client/create', 'Orcamentos\Controller\ClientController::create');

// User controller  
$app->get('/user', 'Orcamentos\Controller\UserController::index');
$app->get('/user/edit/{userId}', 'Orcamentos\Controller\UserController::edit')->value( "userId", null );
$app->get('/user/detail/{userId}', 'Orcamentos\Controller\UserController::detail');

$app->post('/user/create', 'Orcamentos\Controller\UserController::create');

// Project Controller
$app->get('/project', 'Orcamentos\Controller\ProjectController::index');
$app->get('/project/edit/{projectId}', 'Orcamentos\Controller\ProjectController::edit')->value( "projectId", null );
$app->get('/project/detail/{projectId}', 'Orcamentos\Controller\ProjectController::detail');

$app->post('/project/create', 'Orcamentos\Controller\ProjectController::create');

//Company Controller
$app->get('/company', 'Orcamentos\Controller\CompanyController::index');

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