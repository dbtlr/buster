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
        return $this->request->server->get('REQUEST_TIME');
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
    public function getReferrer()
    {
        return $this->request->query->get('r');
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
}