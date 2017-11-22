<?php

namespace HomeOffice\CtsBundle\Tests\Form;

use HomeOffice\CtsBundle\Form\AjaxResponseBuilder;
use HomeOffice\CtsBundle\Form\FormErrorSerializer;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AjaxResponseBuilderTest
 *
 * @package HomeOffice\CtsBundle\Tests\Form
 */
class AjaxResponseBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormErrorSerializer|Mock
     */
    private $formErrorSerializer;

    /**
     * @var Router|Mock
     */
    private $router;

    /**
     * @var TwigEngine|Mock
     */
    private $templating;

    /**
     * @var Form|Mock
     */
    private $form;

    /**
     * Test Get Set Form
     */
    public function testGetSetForm()
    {
        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setForm($this->form), 'Fluid Interface');
        $this->assertSame($this->form, $ajaxResponseBuilder->getForm());
    }

    /**
     * Test Is Set Success
     */
    public function testIsSetSuccess()
    {
        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertFalse($ajaxResponseBuilder->isSuccess(), 'Default success is false');

        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setSuccess(true));
        $this->assertTrue($ajaxResponseBuilder->isSuccess(), 'Can set to true');

        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setSuccess(false));
        $this->assertFalse($ajaxResponseBuilder->isSuccess(), 'Can set to false');
    }

    /**
     * Test Get Set Redirect
     */
    public function testGetSetRedirect()
    {
        $defaultRedirect = '#content_top';
        $newRedirect = '#new_redirect';

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($defaultRedirect, $ajaxResponseBuilder->getRedirect());
        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setRedirect($newRedirect), 'Fluid Interface');
        $this->assertSame($newRedirect, $ajaxResponseBuilder->getRedirect());
    }

    /**
     * Test Get Set Message
     */
    public function testGetSetMessage()
    {
        $defaultMessage = '';
        $newMessage = 'This is the new test message';

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($defaultMessage, $ajaxResponseBuilder->getMessage());
        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setMessage($newMessage), 'Fluid Interface');
        $this->assertSame($newMessage, $ajaxResponseBuilder->getMessage());
    }

    /**
     * Test Get Set Callback No Params
     */
    public function testGetSetCallbackNoParams()
    {
        $defaultCallback = '';
        $expectedCallback = 'function()';

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($defaultCallback, $ajaxResponseBuilder->getMessage());
        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setCallback('function'), 'Fluid Interface');
        $this->assertSame($expectedCallback, $ajaxResponseBuilder->getCallback());
    }

    /**
     * Test Get Set Callback With Param
     */
    public function testGetSetCallbackWithParam()
    {
        $defaultCallback = '';
        $expectedCallback = 'function("param1")';

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($defaultCallback, $ajaxResponseBuilder->getMessage());
        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setCallback('function', ['param1']), 'Fluid Interface');
        $this->assertSame($expectedCallback, $ajaxResponseBuilder->getCallback());
    }

    /**
     * Test Get Set Callback With Params
     */
    public function testGetSetCallbackWithParams()
    {
        $defaultCallback = '';
        $expectedCallback = 'function("param1", "param2")';

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);

        $this->assertSame($defaultCallback, $ajaxResponseBuilder->getMessage());
        $this->assertSame($ajaxResponseBuilder, $ajaxResponseBuilder->setCallback('function', ['param1', 'param2']), 'Fluid Interface');
        $this->assertSame($expectedCallback, $ajaxResponseBuilder->getCallback());
    }

    /**
     * Test Get Set Callback With Params
     */
    public function testCreateResponseSuccess()
    {
        $expectedStatusCode = 200;

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);
        $ajaxResponseBuilder
            ->setSuccess(true)
            ->setMessage('Success Message')
            ->setRedirect('#success_redirect')
            ->setCallback('successCallback', [1]);

        $response = $ajaxResponseBuilder->createResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        $this->assertSame($ajaxResponseBuilder->isSuccess(), $content['success']);
        $this->assertSame($ajaxResponseBuilder->getRedirect(), $content['redirect']);
        $this->assertSame($ajaxResponseBuilder->getMessage(), $content['message']);
        $this->assertSame($ajaxResponseBuilder->getCallback(), $content['callback']);
    }

    /**
     * Test Get Set Callback With Params
     */
    public function testCreateResponseFailure()
    {
        $expectedStatusCode = 400;

        $ajaxResponseBuilder = new AjaxResponseBuilder($this->formErrorSerializer, $this->router, $this->templating);
        $ajaxResponseBuilder
            ->setSuccess(false)
            ->setForm($this->form);

        $response = $ajaxResponseBuilder->createResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        $this->assertSame($ajaxResponseBuilder->isSuccess(), $content['success']);
        $this->assertSame('Please check the form', $content['message']);
        $this->assertNull($content['errors']);
    }

    /**
     * Set Up
     */
    protected function setUp()
    {
        $this->router = $this->getMockBuilder(Router::class)->disableOriginalConstructor()->getMock();
        $this->templating = $this->getMockBuilder(TwigEngine::class)->disableOriginalConstructor()->getMock();
        $this->formErrorSerializer = $this->getMockBuilder(FormErrorSerializer::class)->disableOriginalConstructor()->getMock();

        $this->form = $this->getMockBuilder(Form::class)->disableOriginalConstructor()->getMock();
    }
}
