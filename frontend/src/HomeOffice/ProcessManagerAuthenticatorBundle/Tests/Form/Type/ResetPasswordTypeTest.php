<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\ProcessManagerAuthenticatorBundle\Form\Type\ResetPasswordType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Validator\ConstraintViolationList;

class ResetPasswordTypeTest extends TypeTestCase
{
 
    protected function setUp()
    {
        parent::setUp();

        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(
                new FormTypeValidatorExtension(
                    $validator
                )
            )
            ->addTypeGuesser(
                $this->getMockBuilder(
                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
                )
                    ->disableOriginalConstructor()
                    ->getMock()
            )
            ->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }
 
    public function testResetPasswordSubmitData()
    {
        $formData = array(
            'currentPassword' => 'oldpw',
            'newPassword' => null,
        );
     
        $type = new ResetPasswordType();
        $form = $this->factory->create($type);
     
        $form->submit($formData);
     
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
