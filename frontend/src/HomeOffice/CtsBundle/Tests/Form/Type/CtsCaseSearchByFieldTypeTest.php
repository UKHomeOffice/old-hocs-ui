<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsCaseSearchByFieldType;

class CtsCaseSearchByFieldTypeTest extends TypeTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    public function testCtsCaseGlobalSearchByFieldSubmitData()
    {
        $formData = array(
            'urn' => '12345',
            'dateCreatedFrom' => '1/1/2014',
            'dateCreatedTo' => '31/12/2014',
            'dateDeadlineFrom' => '1/1/2014',
            'dateDeadlineTo' => '31/12/2014',
            'correspondenceType' => 'Ministerial',
            'decision' => 'FAQ',
            'minister' => 'Theresa May',
            'unit' => 'DCU',
            'status' => 'New',
            'task' => 'Create case',
            'topic' => 'Police'
        );
     
        $ctsListHandler = $this->getListHandlerMock();
        $type = new CtsCaseSearchByFieldType($ctsListHandler, false);
        $form = $this->factory->create($type);
     
        $form->submit($formData);
     
        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($ctsCaseMinute, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
