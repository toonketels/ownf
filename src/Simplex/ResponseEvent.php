<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;


/**
 * A new type of event.
 *
 * We define new types so we can store specific data in the event
 * and specify methods to control access.
 *
 * By passing the response object, we can modify it.
 */
class ResponseEvent extends Event
{
  private $request;
  private $response;

  public function __construct(Response $response, Request $request)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function getRequest()
  {
    return $this->request;
  }
}
