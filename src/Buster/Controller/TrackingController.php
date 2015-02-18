<?php

namespace Buster\Controller;

use Buster\Application;
use Buster\Model\Pixel;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackingController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return array
     */
    public function pixelAction(Request $request, Application $app)
    {
        $pixel = new Pixel($request);

        if ($app['debug']) {
            $app->log('pixel parsed', $pixel->parse(), Logger::DEBUG);
        }

        $pixel->write($app['db'], $app);

        $transPixel = base64_decode("R0lGODdhAQABAIAAAPxqbAAAACwAAAAAAQABAAACAkQBADs=");
        $response = new Response($transPixel, 200, array('Content-Type' => 'image/gif'));
        $response->headers->setCookie(new Cookie('bstr', $pixel->getUniqId(), new \DateTime('+25 years')));

        return $response;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return array
     */
    public function pixelRawAction(Request $request, Application $app)
    {
        $pixel = new Pixel($request);

        return $app->json($pixel->parse());
    }
}
