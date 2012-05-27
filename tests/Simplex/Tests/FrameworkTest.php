<?php

namespace Simplex\Tests;

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;


class FrameworkTest extends \PHPUnit_Framework_TestCase
{
  /**
   * Test the Frameworks response when a request's route is not found.
   */
  public function testNotFoundHandling()
  {
    // Helper method to get a Frameworkobject in the desired state to see
    // what it returns. We are creating the state by mocking it's two construct
    // params. This is easier than actually doing a fake request for which we 
    // know there is no route.
    $framework = $this->getFrameworkForException(new ResourceNotFoundException());

    // We need to verify the response
    $response = $framework->handle(new Request());

    $this->assertEquals(404, $response->getStatusCode());
  }

  /**
   * Helper method to instanciate a framework object in the desired state.
   */
  protected function getFrameworkForException($exception)
  {
    // Since the tow objects passed as arguments use a certain interface, we can
    // easily mock them.
    $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
    // We mock matcher->match method is mocked to return a exception.
    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->throwException($exception))
    ;

    // Resolver is mocked too.
    $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');

    return new Framework($matcher, $resolver);
  }

  /**
   * Test handling of runtime errors.
   */
  public function testErrorHandling()
  {
    $framework = $this->getFrameworkForException(new \RuntimeException());

    $response = $framework->handle(new Request());

    $this->assertEquals(500, $response->getStatusCode());
  }

  /**
   * Test a valid request.
   */
  public function testControllerResponse()
  {
    $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->returnValue(array(
        '_route' => 'foo',
        'name' => 'Toon',
        '_controller' => function ($name) {
          return new Response('Hello '.$name);
        }
      )))
    ;

    $resolver = new ControllerResolver();

    $framework = new Framework($matcher, $resolver);

    $response = $framework->handle(new Request());

    $this->assertEquals(200, $response->getStatuscode());
    $this->assertEquals("Hello Toon", $response->getContent());
  }

}
