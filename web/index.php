<?php

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

// Create eventdispatcher
$dispatcher = new EventDispatcher();
// Add eventsubscribers
$dispatcher->addSubscriber(new Simplex\GoogleListener());
$dispatcher->addSubscriber(new Simplex\ContentLengthListener());

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

// Instead of specifically "use" it, grab it via Routing\...
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

// Use Framework to handle request
$framework = new Simplex\Framework($dispatcher, $matcher, $resolver);
// We just add HttpCaching support, implements HttpCacheInterface and
// wraps another HttpCacheInterface implementing object (our app)
$framework = new HttpCache($framework, new Store(__DIR__.'/../cache'));
$response = $framework->handle($request);


$response->send();

