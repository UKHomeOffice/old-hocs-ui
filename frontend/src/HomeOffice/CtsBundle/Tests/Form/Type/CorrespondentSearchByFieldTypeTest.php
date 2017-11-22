<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CorrespondentSearchByFieldType;

class CorrespondentSearchByFieldTypeTest extends TypeTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    public function testCtsCaseCorrespondentSearchByFieldSubmitData()
    {
        $formData = array(
            'dateCreatedFrom' => '1/1/2014',
            'dateCreatedTo' => '31/12/2014',
            'replyForename' => 'Ministerial',
            'replySurname' => 'Fred',
            'postcode' => 'EC01TES',
            'email' => 'm@m.com',
        );
     
        $type = new CorrespondentSearchByFieldType();
        $form = $this->factory->create($type);
     
        $form->submit($formData);
     
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
