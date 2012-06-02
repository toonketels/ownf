<?php

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;

$src = new DependencyInjection\ContainerBuilder();
// RequestContext will be instanciated on demand (now its just registered).
// We will always get the same object back when we request it (Singleton).
// since there objects are "globals".
// We pass a string so the DIC knows how to instanciate it.
$src->register('context', 'Symfony\Component\Routing\RequestContext');
// To instancate matcher, it needs to take two arguments so are provided via
// setArguments.
// One of them is the context, which is already registered so we link to
// it via the Reference object);
$src->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
    ->setArguments(array('%routes%', new Reference('context')));

$src->register('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');

$src->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments(array(new Reference('matcher')));

// We made the charset configurable, it needs to be set via $src->setParamter('charset', 'UTF-8');
$src->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(array('%charset%'));

$src->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
    ->setArguments(array('Calendar\\Controller\\ErrorController::ExceptionAction'));

// AddMethodCall will call the specific methodcs direclty after the object is
// instanciated.
$src->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')));

$src->register('framework', 'Simplex\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')));

return $src;
