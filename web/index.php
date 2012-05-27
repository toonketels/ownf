<?php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Only "use" Routing so we can grab everythign inside it
use Symfony\Component\Routing;
// Need the controller resolver
use Symfony\Component\HttpKernel;

/**
 * This is the default contoller. It extracts the
 * routes from the request and renderds the template
 * (pass a templates content to response object).
 */
function render_template($request)
{
  extract($request->attributes->all(), EXTR_SKIP);
  ob_start();
  include sprintf( __DIR__.'/../src/pages/%s.php', $_route);

  return new Response(ob_get_clean());
}


$request = Request::createFromGlobals();
// Move the routes to own file. This is application specific
// logic and should be seperated from this lib general file.
$routes = include __DIR__.'/../src/app.php';

// Instead of specifically "use" it, grab it via Routing\...
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

// Since requesting a url which is not in the routeCollection
// will throw an error: Catch that exeption and respond with 404.
try {
  $request->attributes->add($matcher->match($request->getPathInfo()));

  // In stead of the controller being a callback, we use objects now.
  // To not instanciate all controller objects we use the controllerResolver
  // to give us the appropriate controller depending on the request.
  $controller = $resolver->getController($request);
  $arguments = $resolver->getArguments($request, $controller);

  $response = call_user_func_array($controller, $arguments);
} catch (Routing\Exception\ResourceNotFoundException $e) {
  $response = new Response('Not Found', 404);
} catch (Exception $e) {
  $response = New Response('An error Occured', 500);
}

$response->send();

