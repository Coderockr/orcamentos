<?php

namespace Orcamentos\Controller;

abstract class BaseController
{
    public function redirectMessage($app, $message, $redirectTo)
    {
		$app['session']->getFlashBag()->clear();
		$app['session']->getFlashBag()->add('message', $message);
		return $app->redirect($redirectTo);		
    }
}