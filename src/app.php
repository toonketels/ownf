<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class LeapYearController
{
  // For the controller to work with controlerResolver the args passed
  // need to be TYPE HINTED so resolver get know which arguments to pass.
  // $year will pass year property by name matching, we can also et default
  // value all thanks to controllerResolver.
  public function indexAction($year) {
    // Defending of it beging a leap year, we return different output.
    if(is_leap_year($year)) {
      return new Response("Yes, this is a leap year.");
    }

    return new Response("No, no leap year.");
  }
}

function is_leap_year($year = null) {
  if($year === null) {
    $year = date('Y');
  }

  return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
}


$routes = new Routing\RouteCollection();

$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
  'year' => null,
  // Without controllerResolver on each request, this controller would be
  // instanciated (even when route is not called)
  // _controller string will be interpreted by controllerResolver and it will
  // instanciated it when route is requested.
  // The indexAction of the LeapYearController will be called to generate
  // the response message.
  '_controller' => 'LeapYearController::indexAction',
)));


return $routes;
