<?php

namespace HomeOffice\CtsBundle\Tests\Routing;

use HomeOffice\CtsBundle\Routing\Router;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Symfony\Bundle\FrameworkBundle\Routing\Router as BaseRouter;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RouterTest
 *
 * @package HomeOffice\CtsBundle\Tests\Routing
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock|BaseRouter
     */
    private $baseRouter;

    /**
     * @var Mock|RequestStack
     */
    private $requestStack;

    /**
     * @var Router
     */
    private $router;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->baseRouter = $this->getMockBuilder(BaseRouter::class)->disableOriginalConstructor()->getMock();
        $this->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();

        $this->router = new Router($this->baseRouter, $this->requestStack);
    }

    /**
     * Test Ssl Redirects False Does NOt Convert To Ssl
     */
    public function testSslRedirectsFalseDoesNotConvertToSsl()
    {
        $this->router = new Router($this->baseRouter, $this->requestStack, false);

        $expectedUrl = 'http://www.test.url/home';

        $this->baseRouter->expects($this->once())->method('generate')->willReturn($expectedUrl);
        $this->assertSame($expectedUrl, $this->router->generate('test_absolute_url'));
    }

    /**
     * Test Router Swaps To Use Ssl For Absolute Urls
     */
    public function testRouterSwapsToUseSslForAbsoluteUrl()
    {
        $inputUrl    = 'http://www.test.url/home';
        $expectedUrl = 'https://www.test.url/home';

        $this->baseRouter->expects($this->once())->method('generate')->willReturn($inputUrl);
        $this->assertSame($expectedUrl, $this->router->generate('test_absolute_url'));
    }

    /**
     * Test Router Ignores Existing Https Urls
     */
    public function testRouterIgnoresExistingHttpsUrls()
    {
        $expectedUrl = 'https://www.test.url/home';

        $this->baseRouter->expects($this->once())->method('generate')->willReturn($expectedUrl);
        $this->assertSame($expectedUrl, $this->router->generate('test_absolute_url'));
    }

    /**
     * Test Router Ignores Relative Urls
     */
    public function testRouterIgnoresRelativeUrls()
    {
        $expectedUrl = '/home/test';

        $this->baseRouter->expects($this->once())->method('generate')->willReturn($expectedUrl);
        $this->assertSame($expectedUrl, $this->router->generate('test_absolute_url'));
    }
}
