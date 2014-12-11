<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 05.12.14
 * Time: 14:59
 */

namespace Pkj\Http;


class Response implements ResponseInterface{



    private $body;


    private $statusCode;


    private $headers;




    private static $messages = array(
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );




    public function __construct ($body = null, $statusCode = self::HTTP_OK) {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = array();
    }



    public function setHeader ($key, $value) {
        $this->headers[$key] = $value;
    }


    public function setBody ($body) {
        $this->body = $body;
    }


    public function httpStatusCodeHeader() {
        return 'HTTP/1.1 ' . self::$messages[$this->statusCode];
    }


    public function getMessageForCode() {
        return self::$messages[$this->statusCode];
    }

    public function isError() {
        return is_numeric($this->statusCode) && $this->statusCode >= self::HTTP_BAD_REQUEST;
    }

    public function canHaveBody($code) {
        return
            // True if not in 100s
            ($code < self::HTTP_CONTINUE || $code >= self::HTTP_OK)
            && // and not 204 NO CONTENT
            $code != self::HTTP_NO_CONTENT
            && // and not 304 NOT MODIFIED
            $code != self::HTTP_NOT_MODIFIED;
    }


    public function send () {
        header($this->httpStatusCodeHeader());

        foreach($this->headers as $header => $value) {
            header("$header: $value");
        }

        if ($this->body) {
            if (!$this->canHaveBody($this->statusCode)) {
                throw new \Exception("Status code {$this->statusCode} can not have any body.");
            }
            echo $this->body;
        }
    }

} 