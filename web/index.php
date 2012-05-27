<?php

require_once __DIR__.'/../autoload.php';


/**
 * HttpFoundation: Object oriented wrapper around request/response.
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$contentPath = __DIR__.'/../pages/';
$map = array(
  '/hello' => $contentPath.'hello.php',
  '/bye' => $contentPath.'bye.php',
);

$path = $request->getPathInfo();
if(isset($map[$path])) {
  ob_start();
  include $map[$path];
  $response->setContent(ob_get_clean());
} else {
  $response->setStatusCode(404);
  $response->setContent('Page not found.');
}

$response->send();

