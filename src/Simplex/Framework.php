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

class Framework implements HttpKernelInterface
{
  protected $matcher;
  protected $resolver;
  protected $dispatcher;

  // Type hint to interfaces for easier testing => they should implement some methods.
  public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $resolver)
  {
    $this->dispatcher = $dispatcher;
    $this->matcher = $matcher;
    $this->resolver = $resolver;
  }

  // HttpKernelInterface...
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
  {
    try {
      $request->attributes->add($this->matcher->match($request->getPathInfo()));

      $controller = $this->resolver->getController($request);
      $arguments = $this->resolver->getArguments($request, $controller);

      $response = call_user_func_array($controller, $arguments);
    } catch (ResourceNotFoundException $e) {
      $response = new Response('Page not found.', 404);
    } catch (\Exception $e) {
      $response =  new Response('Internal error.', 500);
    }

    // dispatch a response event, pass response and requests objects
    // for others to modify
    $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));

    return $response;
  }
}
