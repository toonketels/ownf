<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Reference;

$src = include __DIR__.'/../src/container.php';

// We can exend the default config...
$src->register('listener.string_response', 'Simplex\StringResponseListener');
$src->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')));

// We can use the Dependency Injector Containter to set params.
$src->setParameter('debug', TRUE);

// We made charset configurable, we need to set it.
$src->setParameter('charset', 'UTF-8');
$src->setParameter('routes', include __DIR__.'/../src/app.php');

$request = Request::createFromGlobals();

$src->get('framework')->handle($request)->send();
