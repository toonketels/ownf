<?php

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
  public function onResponse(ResponseEvent $event)
  {
    $response = $event->getResponse();

    if($response->isRedirection()
       || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
       || 'html' !== $event->getRequest()->getRequestFormat()
      ) {
        return;
      }

      $response->setContent($response->getContent().'GA CODE');
  }

  /**
   * GetSubscribedEvents parts of EventSubscriberInterface.
   *
   * We return the events we are interested in and the methods that
   * should be invoked when event in dispatched.
   */
  public static function getSubscribedEvents()
  {
    return array('response' => 'onResponse');
  }
}
