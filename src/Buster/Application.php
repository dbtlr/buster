<?php

namespace Buster;

use Buster\Provider\JsonResponseProvider;
use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
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
        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => require($this['path.config'] . '/database.php'),
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
        $this->register(new MonologServiceProvider(), array('monolog.logfile' => 'php://stderr'));
    }
}
