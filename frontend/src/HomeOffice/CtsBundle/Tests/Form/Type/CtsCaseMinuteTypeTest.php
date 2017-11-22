<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsCaseMinuteType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseMinuteFactory;

class CtsCaseMinuteTypeTest extends TypeTestCase
{


    protected function setUp()
    {
        parent::setUp();
    }

    public function testCtsCaseMinuteSubmitData()
    {
        $formData = array(
            'minuteContent'             => 'Minute test',
        );

        $type = new CtsCaseMinuteType();
        $form = $this->factory->create($type);

        $factory = new CtsCaseMinuteFactory();
        $ctsCaseMinute = $factory->build($formData);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsCaseMinute, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
