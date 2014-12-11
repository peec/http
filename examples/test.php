<?php

require "../vendor/autoload.php";

use Pkj\Http\Request,
    Pkj\Http\Response;


$request = Request::bindFromHttpFactory();


$response = new Response();
$response->setBody("Hello World from {$request->getServerDomain()}.");
$response->send();


