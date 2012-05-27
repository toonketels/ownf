<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
  'year' => null,
  '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction',
)));


return $routes;
