<?php
require_once __DIR__.'/bootstrap.php';

use Silex\Application,
    Silex\Provider\DoctrineServiceProvider,
    Symfony\Component\HttpFoundation\Request,
    Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app['debug'] = true;

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
    
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'username' => 'contato@coderockr.com',
    'password' => 'H&m6&mUE',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
);

$app->before(function (Request $request) use ($app) {
    $requestUri = $request->getRequestUri();

    $allowedNotLoggedRoutes = array('','logout', 'login', 'share');
    $route = explode('/', $requestUri);

    if (!in_array($route[1], $allowedNotLoggedRoutes)
        && $app['session']->get('email') == null ) {
        return $app->redirect('/');
    }

    $allowedAdminRoutes = array('','client', 'quote','user','company','share','status');

    $route = explode('/', $requestUri);
    if(in_array($route[1], $allowedAdminRoutes) && $app['session']->get('email') != null && $app['session']->get('isAdmin') == false ) {
        return $app->redirect('/project');
    }

});

$app->error(function (\Exception $e, $code) use($app) {
    switch ($code) {
        case 404:
            $message = $app['twig']->render('error404.twig', array('code'=>$code, 'message' => 'Página não encontrada.'));
            break;
        default:
            $message = $e->getMessage() . ' no arquivo ' . $e->getFile() . ', na linha: '. $e->getLine();
            break;
    }
    return new Response($message, $code);
});
    
// Group controllers by route
$index = $app['controllers_factory'];
$status = $app['controllers_factory'];
$client = $app['controllers_factory'];
$user = $app['controllers_factory'];
$project = $app['controllers_factory'];
$company = $app['controllers_factory'];
$quote = $app['controllers_factory'];
$share = $app['controllers_factory'];
$resource = $app['controllers_factory'];

// Index controller Routes
$index->get('/', 'Orcamentos\Controller\IndexController::index');

//Status controller Routes
$status->get('/', 'Orcamentos\Controller\StatusController::index');

//Client Controller Routes
$client->get('/edit/{clientId}', 'Orcamentos\Controller\ClientController::edit')->value( "clientId", null );
$client->get('/detail/{clientId}', 'Orcamentos\Controller\ClientController::detail');
$client->post('/create', 'Orcamentos\Controller\ClientController::create');
$client->get('/search/{page}', 'Orcamentos\Controller\ClientController::search')->value('page', 1);
$client->get('/delete/{clientId}', 'Orcamentos\Controller\ClientController::delete');
$client->get('{page}', 'Orcamentos\Controller\ClientController::index')->value('page', 1);

//User Controller Routes
$user->get('/edit/{userId}', 'Orcamentos\Controller\UserController::edit')->value( "userId", null );
$user->get('/detail/{userId}', 'Orcamentos\Controller\UserController::detail');
$user->post('/create', 'Orcamentos\Controller\UserController::create');
$user->get('/delete/{userId}', 'Orcamentos\Controller\UserController::delete');
$user->get('/{page}', 'Orcamentos\Controller\UserController::index')->value('page', 1);

// Project Controller
$project->get('/edit/{projectId}', 'Orcamentos\Controller\ProjectController::edit')->value( "projectId", null );
$project->get('/detail/{projectId}', 'Orcamentos\Controller\ProjectController::detail');
$project->get('/delete/{projectId}', 'Orcamentos\Controller\ProjectController::delete');
$project->get('/new/{clientId}', 'Orcamentos\Controller\ProjectController::edit');
$project->get('/removeComment/{noteId}', 'Orcamentos\Controller\ProjectController::removeComment');
$project->post('/create', 'Orcamentos\Controller\ProjectController::create');
$project->post('/comment', 'Orcamentos\Controller\ProjectController::comment');
$project->get('/search/{page}', 'Orcamentos\Controller\ProjectController::search')->value('page', 1);
$project->get('{page}', 'Orcamentos\Controller\ProjectController::index')->value('page', 1);

//Company Controller
$company->get('/', 'Orcamentos\Controller\CompanyController::edit');
$company->post('/create', 'Orcamentos\Controller\CompanyController::create');

//quote Controller
$quote->get('/new/{projectId}', 'Orcamentos\Controller\QuoteController::edit');
$quote->get('/edit/{quoteId}', 'Orcamentos\Controller\QuoteController::edit');
$quote->get('/detail/{quoteId}', 'Orcamentos\Controller\QuoteController::detail');
$quote->get('/preview/{quoteId}', 'Orcamentos\Controller\QuoteController::preview');
$quote->get('/delete/{quoteId}', 'Orcamentos\Controller\QuoteController::delete');
$quote->get('/duplicate/{quoteId}', 'Orcamentos\Controller\QuoteController::duplicate');
$quote->post('/create', 'Orcamentos\Controller\QuoteController::create');

// Share Controller
$share->get('/delete/{shareId}', 'Orcamentos\Controller\ShareController::delete');
$share->post('/create', 'Orcamentos\Controller\ShareController::create');
$share->post('/comment', 'Orcamentos\Controller\ShareController::comment');
$share->get('/removeComment/{shareNoteId}', 'Orcamentos\Controller\ShareController::removeComment');
$share->post('/resend', 'Orcamentos\Controller\ShareController::resend');
$share->get('/sendEmails/{limit}', 'Orcamentos\Controller\ShareController::sendEmails')->value('limit', 10);
$share->get('/{hash}', 'Orcamentos\Controller\ShareController::detail');

//Resource Controller
$resource->get('/', 'Orcamentos\Controller\ResourceController::index');
$resource->post('/create', 'Orcamentos\Controller\ResourceController::create');
$resource->get('/delete/{resourceId}', 'Orcamentos\Controller\ResourceController::delete');
$resource->get('/get', 'Orcamentos\Controller\ResourceController::get');

//Admin Controller
$app->post('/login', 'Orcamentos\Controller\AdminController::login');
$app->get('/logout', 'Orcamentos\Controller\AdminController::logout');

$app->mount('/', $index);
$app->mount('/status', $status);
$app->mount('/client', $client);
$app->mount('/user', $user);
$app->mount('/project', $project);
$app->mount('/company', $company);
$app->mount('/quote', $quote);
$app->mount('/share', $share);
$app->mount('/resource', $resource);

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