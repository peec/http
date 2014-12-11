<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 03.12.14
 * Time: 10:25
 */

namespace Pkj\Http;


class Request implements RequestInterface{


    private $url;

    private $basePath;

    private $method;

    private $baseScript;

    private $serverDomain;


    /**
     * @return \Pkj\Http\Request
     */
    static public function bindFromHttpFactory () {
        $request = new Request();

        $request->parseFromGlobal('basePath', function () {
            $basePath = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : null;
            $basePath = dirname($basePath);
            return $basePath;
        });

        $request->parseFromGlobal('baseScript', function () {

            $basePath = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : null;
            $baseScript = basename($basePath);
            return $baseScript;
        });

        $request->parseFromGlobal('method', function () {
            return self::bitOfRequestMethod($_SERVER['REQUEST_METHOD']);
        });

        $request->parseFromGlobal('url', function () {
            // Strip path up to correct format.
            if (!isset($_SERVER['PATH_INFO'])) {
                $path = '/';
            } else {
                $path = $_SERVER['PATH_INFO'];
                if (!$path) {
                    $path = '/';
                }
            }
            return $path;
        });

        $request->parseFromGlobal('serverDomain', function () {
            return $_SERVER['SERVER_NAME'];
        });

        return $request;
    }


    public function parseFromGlobal($var, callable $callback) {
        call_user_func(array($this, "set" . ucfirst($var)), call_user_func($callback));
    }


    static public function bitOfRequestMethod ($method) {
        switch(strtoupper($method)) {
            case "GET":
                return self::GET;
                break;
            case "POST":
                return self::POST;
                break;
            case "DELETE":
                return self::DELETE;
                break;
            case "PATCH":
                return self::PATCH;
                break;
            case "PUT":
                return self::PUT;
                break;
            case "*":
                return self::WILDCARD;
                break;
        }
    }

    /**
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $baseScript
     */
    public function setBaseScript($baseScript)
    {
        $this->baseScript = $baseScript;
    }

    /**
     * @return mixed
     */
    public function getBaseScript()
    {
        return $this->baseScript;
    }


    public function getBaseUrl () {
        return $this->getBasePath() . '/' . $this->getBaseScript();
    }

    /**
     * @param mixed $serverDomain
     */
    public function setServerDomain($serverDomain)
    {
        $this->serverDomain = $serverDomain;
    }

    /**
     * @return mixed
     */
    public function getServerDomain()
    {
        return $this->serverDomain;
    }



    public function requestedUrl () {
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
    }


} 