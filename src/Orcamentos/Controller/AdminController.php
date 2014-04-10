<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Crypt\Password\Bcrypt;
use Exception;

class AdminController
{
	public function login(Request $request, Application $app)
	{
		$data = $request->request->all();

		if ( !isset($data['email']) || !isset($data['password'])) {
			throw new Exception("Error Processing Request", 1);
		}

		$user = $app['orm.em']->getRepository('Orcamentos\Model\User')->findOneBy(array( 'email' => $data['email'] ));
		
		if ( !$user ) {
			throw new Exception("Error Processing Request", 1);
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
		
		return $app->redirect('/');		
	}	

	public function logout(Request $request, Application $app)
	{
		$app['session']->set('email', null);
		$app['session']->set('isAdmin', null);
		$app['session']->set('companyId', null);
		
		return $app->redirect('/');		
	}
}