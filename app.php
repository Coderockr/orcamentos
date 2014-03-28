<?php
require_once __DIR__.'/bootstrap.php';

$app = new Silex\Application();

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
$app->get('/client/create', 'Orcamentos\Controller\ClientController::create');
$app->get('/client/detail/{client}', 'Orcamentos\Controller\ClientController::detail');

// Project Controller
$app->get('/project', 'Orcamentos\Controller\ProjectController::index');
