<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpKernel\HttpKernel;


// Subclass HttpKernel
class Framework extends HttpKernel
{
}
