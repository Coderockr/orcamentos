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
			$app['session']->getFlashBag()->add('message', 'Email e/ou senha inválidos!');
			return $app->redirect('/');			
		}

		$user = $app['orm.em']->getRepository('Orcamentos\Model\User')->findOneBy(array( 'email' => $data['email'] ));
		
		if ( !$user ) {
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
		
		if($user->getAdmin()){
			return $app->redirect('/');		
		}

		return $app->redirect('/project');		
	}	

	public function logout(Request $request, Application $app)
	{
		$app['session']->set('email', null);
		$app['session']->set('isAdmin', null);
		$app['session']->set('companyId', null);
		$app['session']->set('companyLogotype', null);
		$app['session']->set('companyName', null);
		
		return $app->redirect('/');		
	}
}