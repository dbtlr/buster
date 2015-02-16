<?php

namespace Buster\Controller;

use Buster\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return \Twig_Environment
     */
    public function pixelateAction(Request $request, Application $app)
    {
        $trackDomain = $request->getHost();
        $port   = $request->getPort();
        $local  = $request->getHost() == 'localhost';

        if ($local || $port != 80 || $port != 443) {
            $trackDomain .= ':' . $port;
        }


        return $app['twig']->render(
            'pixelate.twig',
            array(
                'domainName'  => $request->getHost(),
                'trackDomain' => $trackDomain,
                'httpsOff' => $local,
            )
        );
    }
}
