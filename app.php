<?php
require_once __DIR__.'/bootstrap.php';

$app = new Silex\Application();

//configuration
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

//actions
$app->get('/', function ()  use ($app, $em) {
	$q = $em->createQuery("select u from Coderockr\Model\User u");
    $users = $q->getResult();
	
    return $app['twig']->render('user.twig', array(
        'users' => $users
	));
});

$app->post('/user', function() use ($app, $em) {
    $name = $app['request']->get('name');
    $login = $app['request']->get('login');
    $email = $app['request']->get('email');
    $user = $em->getRepository('Coderockr\Model\User')->findBy(array('login' => $login));
    if (count($user) == 0) {
        $user = new Coderockr\Model\User();
        $user->setName($name);
        $user->setLogin($login);
        $user->setEmail($email);

        $em->persist($user);
        $em->flush();
        return $app->redirect('/');
    }
    return $app['twig']->render('message.twig', array(
        'message' => 'User exists'
    ));
});
