<?php

namespace Buster;

use Buster\Provider\JsonResponseProvider;
use Silex\Application as SilexApplication;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Buster\Provider\ErrorHandlerProvider;

class Application extends SilexApplication
{
    use SilexApplication\MonologTrait;

    /**
     * Instantiate a new Application.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this['debug'] = php_sapi_name() === 'cli-server';

        $this->registerLogging();
        $this->register(new JsonResponseProvider());

        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['path.view'],
        ));
    }

    /**
     * @return ErrorHandlerProvider
     */
    protected function registerErrorHandler()
    {
        $errorProvider = new ErrorHandlerProvider;
        $this->register($errorProvider);

        return $errorProvider;
    }

    /**
     *
     */
    protected function registerLogging()
    {
        $logFile = $this['path.log'] . '/system.log';
        if (php_sapi_name() === 'cli-server') {
            $logFile = 'php://stderr';
        }

        $this->register(new MonologServiceProvider(), array('monolog.logfile' => $logFile));
    }
}
