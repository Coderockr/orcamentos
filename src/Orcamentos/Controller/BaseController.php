<?php

namespace Orcamentos\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;

abstract class BaseController implements ControllerProviderInterface
{

    abstract public function mount($controllers);

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $this->mount($controllers);
        return $controllers;
    }

    public static function getPublicActions()
    {
        return array();
    }

    public static function isPublic($action)
    {
        return in_array($action, static::getPublicActions());
    }

    public static function getGuestActions()
    {
        return array();
    }

    public static function isGuest($action)
    {
        return in_array($action, static::getGuestActions());
    }

    public function redirectMessage($app, $message, $redirectTo)
    {
        $app['session']->getFlashBag()->clear();
        $app['session']->getFlashBag()->add('message', $message);
        return $app->redirect($redirectTo);
    }
}
