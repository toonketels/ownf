<?php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Only "use" Routing so we can grab everythign inside it
use Symfony\Component\Routing;

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

// Since requesting a url which is not in the routeCollection
// will throw an error: Catch that exeption and respond with 404.
try {
  $request->attributes->add($matcher->match($request->getPathInfo()));
  // To get the response content: ask the route to execute it's _controller
  // callback function. Each route should hafe this _controller key.
  // A controller can be a custom callback function or the default
  // render_template above.
  $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $e) {
  $response = new Response('Not Found', 404);
} catch (Exception $e) {
  $response = New Response('An error Occured', 500);
}

$response->send();

