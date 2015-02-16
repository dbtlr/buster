<?php

namespace Buster\Model;

use Symfony\Component\HttpFoundation\Request;

class Pixel
{
    /**
     * @var Request
     */
    protected $request;

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
     * @return array
     */
    public function getUserAgent()
    {
        return array(
            'raw' => $this->request->server->get('HTTP_USER_AGENT'),
        );
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->request->query->all();
    }

    /**
     * @return string
     */
    public function getRemoteAddr()
    {
        return $this->request->server->get('REMOTE_ADDR');
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
        return $this->request->server->get('HTTP_HOST');
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->request->server->get('SERVER_NAME');
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        return $this->request->server->get('REQUEST_TIME');
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        return $this->request->server->get('HTTP_REFERER');
    }

    /**
     * @return string
     */
    public function getLanguage()
    {

    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->request->query->get('u');
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->request->query->get('k');
    }

    /**
     * @return string
     */
    public function getRouteId()
    {
        return $this->request->query->get('r');
    }

    /**
     * @return string
     */
    public function getRouteParams()
    {
        return $this->request->query->get('p');
    }

    /**
     * @return string
     */
    public function getScreenSize()
    {
        return $this->request->query->get('s');
    }

    public function parse()
    {
        return array(
            'cookies'     => $this->getCookies(),
            'host'        => $this->getHost(),
            'referrer'    => $this->getReferrer(),
            'userAgent'   => $this->getUserAgent(),
            'language'    => $this->getLanguage(),
            'geo'         => $this->getGeo(),
            'remoteAddr'  => $this->getRemoteAddr(),
            'domain'      => $this->getDomain(),
            'requestTime' => $this->getRequestTime(),
            'params'      => $this->getParams(),
            'raw'         => $this->request->server->all()
        );
    }
}