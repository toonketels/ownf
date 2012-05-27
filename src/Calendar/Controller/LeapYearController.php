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
    if($leapyear->isLeapYear($year)) {
      return new Response("Yes sir, it is a leapyear.");
    }

    return new Response("No, this is not a leapyear!");
  }
}
