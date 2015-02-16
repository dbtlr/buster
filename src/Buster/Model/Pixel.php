<?php

namespace Buster\Model;

use Monolog\Logger;
use Buster\Application;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class Pixel
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $uniqId;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->request->cookies->all();
    }

    /**
     * @param string|null $key
     * @return array
     */
    public function getUserAgent($key = null)
    {
        $ua = array(
            'raw' => $this->request->server->get('HTTP_USER_AGENT'),
        );

        return isset($ua[$key]) ? $ua[$key] : $ua;
    }

    /**
     * @return string
     */
    public function getRemoteAddr()
    {
        return $this->request->getClientIp();
    }

    /**
     * @return array
     */
    public function getGeo()
    {

    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->request->getHost();
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        $time = $this->request->server->get('REQUEST_TIME');

        $date = new \DateTime('@' . $time);

        return $date->format('r');
    }

    /**
     * @return string
     */
    public function getPixelReferrer()
    {
        return $this->request->server->get('HTTP_REFERER');
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUniqId()
    {
        if ($this->uniqId) {
            return $this->uniqId;
        }

        if (!$this->uniqId = $this->request->cookies->get('bstr', null)) {
            $this->uniqId = Uuid::uuid4()->toString();
        }

        return $this->uniqId;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->request->query->get('i');
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->request->query->get('d');
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return @parse_url($this->request->query->get('dp', $this->getPixelReferrer()), \PHP_URL_PATH);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->request->query->get('t', 'pageview');
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        return $this->request->query->get('r', null);
    }

    /**
     * @return string
     */
    public function getReferrerDomain()
    {
        $referrer = $this->getReferrer();

        return @parse_url($referrer, \PHP_URL_HOST);
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->request->query->get('p');
    }

    /**
     * @return string
     */
    public function getScreenSize()
    {
        return (array) @json_decode($this->request->query->get('s'), true);
    }

    /**
     * @return array
     */
    public function parse()
    {
        return array(
            'cookies'     => $this->getCookies(),
            'referrer'    => $this->getReferrer(),
            'userAgent'   => $this->getUserAgent(),
            'language'    => $this->getLanguage(),
            'geo'         => $this->getGeo(),
            'remoteAddr'  => $this->getRemoteAddr(),
            'domain'      => $this->getDomainName(),
            'requestTime' => $this->getRequestTime(),
            'screenSize'  => $this->getScreenSize(),
            'params'      => $this->getParams(),
            'raw'         => $this->request->server->all()
        );
    }

    /**
     * @param Connection $db
     * @param Application $app
     */
    public function write(Connection $db, Application $app = null)
    {
        $data = array(
            'uniqid' => $this->getUniqId(),
            'requesttime' => $this->getRequestTime(),
            'type' => $this->getType(),
            'identity' => $this->getIdentity(),
            'domain' => $this->getDomainName(),
            'url' => $this->getPath(),
            'referrer' => $this->getReferrer(),
            'referrerdomain' => $this->getReferrerDomain(),
            'remoteaddr' => $this->getRemoteAddr(),
            'useragent' => $this->getUserAgent('raw'),
            'meta' => $this->getMeta(),
        );

        $qb = $db->createQueryBuilder();
        $qb->insert('tracking')
            ->values(array(
                'uniqid' => ':uniqid',
                'requesttime' => ':requesttime',
                'type' => ':type',
                'identity' => ':identity',
                'domain' => ':domain',
                'url' => ':url',
                'referrer' => ':referrer',
                'referrerdomain' => ':referrerdomain',
                'remoteaddr' => ':remoteaddr',
                'useragent' => ':useragent',
                'meta' => ':meta',
            ))
            ->setParameters($data);

        if ($app && $app['debug']) {
            $app->log('Query: ' . $qb->getSQL(), $data, Logger::INFO);
        }

        $qb->execute();
    }
}