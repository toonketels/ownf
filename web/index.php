<?php

require_once __DIR__.'/../vendor/autoload.php';


/**
 * HttpFoundation: Object oriented wrapper around request/response.
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = array(
  '/hello' => 'hello',
  '/bye' => 'bye',
);

$path = $request->getPathInfo();
if(isset($map[$path])) {
  ob_start();
  extract($request->query->all(), EXTR_SKIP);
  include sprintf(__DIR__.'/../src/pages/%s.php',$map[$path]);
  $response->setContent(ob_get_clean());
} else {
  $response->setStatusCode(404);
  $response->setContent('Page not found.');
}

$response->send();

