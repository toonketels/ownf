<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

function is_leap_year($year = null) {
  if($year === null) {
    $year = date('Y');
  }

  return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
}


$routes = new Routing\RouteCollection();

$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
  'year' => null,
  '_controller' => function($request) {
    // Defending of it beging a leap year, we return different output.
    if(is_leap_year($request->attributes->get('year'))) {
      return new Response("Yes, this is a leap year.");
    }

    return new Response("No, no leap year.");
  },
)));


return $routes;
