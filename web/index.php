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
$dispatcher->addListener('response', 'Simplex\GoogleListener::onResponse');
$dispatcher->addListener('response', 'Simplex\ContentLengthListener::onResponse', -255);

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

