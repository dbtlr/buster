<?php

namespace Buster\Provider;

use Buster\Application;
use Silex\Application as SilexApplication;
use Silex\ServiceProviderInterface;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * ErrorHandler Provider
 */
class ErrorHandlerProvider implements ServiceProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param SilexApplication $app
     */
    public function register(SilexApplication $app)
    {
        $this->app = $app;

        $this->registerExceptionHandler();
        $this->registerErrorHandler();
        $this->registerShutdownFunction();
        $this->registerErrorEvent();
    }

    /**
     * @param SilexApplication $app
     */
    public function boot(SilexApplication $app)
    {

    }

    /**
     * Set the error handler for showing error pages.
     */
    protected function registerErrorEvent()
    {
        $app = $this->app;

        $this->app->error(
            function (\Exception $exception, $code) use ($app) {
                $payload = array('message' => '', 'code' => $code);
                switch ($code) {
                    case 404:
                        $payload['message'] = 'The requested resource could not be found.';
                        break;

                    case 500:
                    default:
                        $payload = $this->convertExceptionToPayload($exception, $app);

                        $app->log($exception->getMessage(), $payload, Logger::CRITICAL);

                }

                return $this->toResponse($this->app, $payload, $code);
            }
        );
    }

    /**
     * @param Application $app
     * @param array $payload
     * @param int $code
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    protected function toResponse(Application $app, array $payload, $code)
    {
        if (!$app['cli']) {
            $response = $app->json($payload, $code);

        } else {
            $response = new Response($this->printPayload($payload), $code);
        }

        return $response;
    }

    /**
     *
     */
    protected function registerExceptionHandler()
    {
        set_exception_handler(array($this, 'handleException'));
    }

    /**
     *
     */
    protected function registerErrorHandler()
    {
        set_error_handler(array($this, 'handleError'), error_reporting());
    }

    /**
     *
     */
    public function registerShutdownFunction()
    {
        register_shutdown_function(array($this, 'handleFatalError'));
    }

    /**
     *
     */
    public function handleFatalError()
    {
        if (null === $lastError = error_get_last()) {
            return;
        }

        $errors = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_STRICT;

        if ($lastError['type'] & $errors) {
            $e = new \ErrorException(
                @$lastError['message'],
                @$lastError['type'],
                @$lastError['type'],
                @$lastError['file'],
                @$lastError['line']
            );

            $this->handleException($e);
        }
    }

    /**
     * @param $code
     * @param $message
     * @param string $file
     * @param int $line
     */
    public function handleError($code, $message, $file = '', $line = 0)
    {
        $e = new \ErrorException($message, 0, $code, $file, $line);
        $this->handleException($e);
    }

    /**
     * @param \Exception $e
     */
    public function handleException(\Exception $e)
    {
        $payload = $this->convertExceptionToPayload($e, $this->app);

        $this->app->log($e->getMessage(), $payload, Logger::CRITICAL);

        $response = $this->toResponse($this->app, $payload, 500);

        $response->send();
    }

    /**
     * @param array $payload
     * @return string
     */
    protected function printPayload(array $payload)
    {
        if (!isset($payload['error'])) {
            return 'Something really went wrong. No error attached.';
        }

        $error = $payload['error'];

        $response  = "Oh no, something went wrong!\n\nMessage: %s\nFile: %s\nLine: %s\nType: %s\nStacktrace:\n%s\n";

        $response = sprintf(
            $response,
            $error['message'],
            $error['file'],
            $error['line'],
            $error['type'],
            '  ' . implode("\n  ", $error['stacktrace'])
        );

        return $response;
    }

    /**
     * @param \Exception $exception
     * @param Application $app
     * @return array
     */
    protected function convertExceptionToPayload(\Exception $exception, Application $app)
    {
        $code = 500;
        $message = 'We are sorry, but something went terribly wrong.';

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
        }

        $payload = array('message' => $message, 'code' => $code);

        if ($app['debug']) {
            $payload['error'] = array(
                'message'    => $exception->getMessage(),
                'file'       => $exception->getFile(),
                'line'       => $exception->getLine(),
                'type'       => get_class($exception),
                'stacktrace' => explode("\n", $exception->getTraceAsString()),
            );
        }

        return $payload;
    }
}
