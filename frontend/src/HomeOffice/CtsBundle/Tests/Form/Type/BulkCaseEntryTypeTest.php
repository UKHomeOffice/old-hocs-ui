<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\BulkCaseEntryType;
use HomeOffice\AlfrescoApiBundle\Factory\BulkDocumentFactory;

class BulkCaseEntryTypeTest extends TypeTestCase
{

    public function testBulkCaseEntrySubmitData()
    {
        $formData = array(
            'correspondenceType' => 'TYPE',
        );

        $type = new BulkCaseEntryType(
            $this->getListHandlerMock(),
            array(),
            array()
        );

        $form = $this->factory->create($type);

        $factory = new BulkDocumentFactory('workspace', 'store');
        $bulkDocument = $factory->build($formData);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($bulkDocument, $factory->build($form->getData()));

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
