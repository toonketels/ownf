<?php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

// Create eventdispatcher
$dispatcher = new EventDispatcher();
// Add eventlistener
$dispatcher->addListener('response', function(Simplex\ResponseEvent $event) {
  $response = $event->getResponse();

  if($response->isRedirection()
     || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'Html'))
     || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
      return;
    }

    $response->setContent($response->getContent().'GA CODE');
});

$dispatcher->addListener('response', function(Simplex\ResponseEvent $event) {
  $response = $event->getResponse();
  $headers = $response->headers;

  if(!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
    $headers->set('Content-Lenght', strlen($response->getContent()));
  }
}, -255);


$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

// Instead of specifically "use" it, grab it via Routing\...
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

// Use Framework to handle request
$framework = new Simplex\Framework($dispatcher, $matcher, $resolver);
$response = $framework->handle($request);


$response->send();

