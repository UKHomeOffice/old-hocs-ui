<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\EventListener;

use HomeOffice\ProcessManagerAuthenticatorBundle\EventListener\AjaxAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class AjaxAuthenticationListenerTest
 *
 * @package HomeOffice\ProcessManagerAuthenticatorBundle\Tests\EventListener
 */
class AjaxAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Only Handle Ajax Requests
     */
    public function testOnlyHandleAjaxRequests()
    {
        $event = $this->getMockBuilder(GetResponseForExceptionEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->never())->method('setResponse');

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('isXmlHttpRequest')->willReturn(false);
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        $exception = new \Exception();
        $event->expects($this->once())->method('getException')->willReturn($exception);

        $ajaxAuthenticationListener = new AjaxAuthenticationListener();
        $ajaxAuthenticationListener->onCoreException($event);
    }

    /**
     * Test 403 Response for AuthenticationException
     */
    public function test403ResponseForAuthenticationException()
    {
        $event = $this->getMockBuilder(GetResponseForExceptionEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->once())->method('setResponse');

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('isXmlHttpRequest')->willReturn(true);
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        $exception = new AuthenticationException();
        $event->expects($this->once())->method('getException')->willReturn($exception);

        $ajaxAuthenticationListener = new AjaxAuthenticationListener();
        $ajaxAuthenticationListener->onCoreException($event);
    }

    /**
     * Test 403 Response for AccessDeniedException
     */
    public function test403ResponseForAccessDeniedException()
    {
        $event = $this->getMockBuilder(GetResponseForExceptionEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->once())->method('setResponse');

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('isXmlHttpRequest')->willReturn(true);
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        $exception = new AccessDeniedException();
        $event->expects($this->once())->method('getException')->willReturn($exception);

        $ajaxAuthenticationListener = new AjaxAuthenticationListener();
        $ajaxAuthenticationListener->onCoreException($event);
    }
}
