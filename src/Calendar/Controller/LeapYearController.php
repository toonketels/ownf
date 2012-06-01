<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController
{
  public function indexAction(Request $request, $year)
  {
    $leapyear = new LeapYear();

    $number = rand(0,10000000);

    if($leapyear->isLeapYear($year)) {
      $response = new Response("Yes sir, it is a leapyear in $number.");
    } else {
      $response = new Response("No, this is not a leapyear in $number!");
    }
    // Add cache header: Set Time to live to 10 seconds
    $response->setTtl(10);

    return $response;;
  }

}
