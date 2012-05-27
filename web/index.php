<?php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Only "use" Routing so we can grab everythign inside it
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
// Move the routes to own file. This is application specific
// logic and should be seperated from this lib general file.
$routes = include __DIR__.'/../src/app.php';

// Instead of specifically "use" it, grab it via Routing\...
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

// Since requesting a url which is not in the routeCollection
// will throw an error: Catch that exeption and respond with 404.
try {
  // Will return the parts of the current path: first item is always _route.
  extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
  ob_start();
  include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

  $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $e) {
  $response = new Response('Not Found', 404);
} catch (Exception $e) {
  $response = New Response('An error Occured', 500);
}

$response->send();

