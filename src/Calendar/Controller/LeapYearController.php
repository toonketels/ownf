<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Calendar\Model\LeapYear;

// Allow controller to just return strings instead of a response object.
// We add a listener who will grab the string and make a
// response object out of it. It wil listen for the kernel.view event
// which will be fired just after the controller is done returning the string.
class LeapYearController
{
  public function indexAction(Request $request, $year)
  {
    $leapyear = new LeapYear();

    if($leapyear->isLeapYear($year)) {
      return "Yes sir, it is a leapyear.";
    }

    return"No, this is not a leapyear!";
  }

}
