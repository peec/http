<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 05.12.14
 * Time: 15:17
 */

namespace Pkj\Http;


interface RequestInterface {
    const GET = 1;
    const POST = 2;
    const DELETE = 4;
    const PATCH = 8;
    const PUT = 16;
    const WILDCARD = 31;


    public function getBasePath();

    public function getBaseScript();

    public function getMethod();

    public function getUrl();

    public function getServerDomain();

    public function requestedUrl();

} 