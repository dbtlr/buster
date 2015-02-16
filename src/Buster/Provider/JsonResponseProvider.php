<?php

namespace Buster\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * Route Provider
 */
class JsonResponseProvider implements ServiceProviderInterface
{
    /** @var Application */
    protected $app;

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->on(KernelEvents::VIEW, array($this, 'convertArrayToJsonResponse'));
    }

    /**
     * Takes the given event and if the response is in array, then this will convert it to a json response.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function convertArrayToJsonResponse(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!is_array($result)) {
            return;
        }

        $code = isset($result['code']) ? (int) $result['code'] : 200;

        $event->setResponse($this->app->json($result), $code);
    }
}