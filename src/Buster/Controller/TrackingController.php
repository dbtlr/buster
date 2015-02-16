<?php

namespace Buster\Controller;

use Buster\Application;
use Buster\Model\Pixel;
use Monolog\Logger;
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

        $transPixel = base64_decode("R0lGODdhAQABAIAAAPxqbAAAACwAAAAAAQABAAACAkQBADs=");
        return new Response($transPixel, 200, array('Content-Type' => 'image/gif'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function pixelRawAction(Request $request)
    {
        $pixel = new Pixel($request);

        return $pixel->parse();
    }
}