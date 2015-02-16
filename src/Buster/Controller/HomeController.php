<?php

namespace Buster\Controller;

use Buster\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function pixelateAction(Request $request, Application $app)
    {
        return $app['twig']->render('pixelate.twig');
    }
}
