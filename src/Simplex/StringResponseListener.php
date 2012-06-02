<?php

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEVent;
use Symfony\Component\HttpFoundation\Response;


// Watches over Controllers responses. If they are strings, will modify
// response to a Response object. When already a response object, does
// nothing...
class StringResponseListener implements EventSubscriberInterface
{
  public function onView(getResponseForControllerResultEvent $event)
  {
    $response = $event->getControllerResult();

    if(is_string($response)) {
      $event->setResponse(new Response($response));
    }
  }

  public static function getSubscribedEvents()
  {
    return array('kernel.view' => 'onView');
  }
}
