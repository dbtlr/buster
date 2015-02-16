<?php

namespace Buster;

use Buster\Provider\JsonResponseProvider;
use Monolog\Logger;
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

        $this->registerDatabase();
    }

    /**
     *
     */
    protected function registerDatabase()
    {
        $url = null;

        if (isset($_ENV['HEROKU_POSTGRESQL_GREEN_URL'])) {
            $url = $_ENV['HEROKU_POSTGRESQL_GREEN_URL'];
        }

        $this->log('Url: ' . $url, array(), Logger::DEBUG);
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
        $this->register(new MonologServiceProvider(), array('monolog.logfile' => 'php://stderr'));
    }
}
