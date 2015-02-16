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
        $host     = 'localhost';
        $port     = '5432';
        $username = 'buster';
        $password = '';
        $dbname   = 'buster';

        $url = null;
        $parsedUrl = array();

        // USE HEROKU DATABASE CONFIG
        if (isset($_ENV['HEROKU_POSTGRESQL_GREEN_URL'])) {
            $url = $_ENV['HEROKU_POSTGRESQL_GREEN_URL'];
            $parsedUrl = parse_url($url);
        }

        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_pgsql',
                'dbname'   => isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : $dbname,
                'host'     => isset($parsedUrl['host']) ? $parsedUrl['host'] : $host,
                'port'     => isset($parsedUrl['port']) ? $parsedUrl['port'] : $port,
                'user'     => isset($parsedUrl['user']) ? $parsedUrl['user'] : $username,
                'password' => isset($parsedUrl['pass']) ? $parsedUrl['pass'] : $password,
            ),
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
