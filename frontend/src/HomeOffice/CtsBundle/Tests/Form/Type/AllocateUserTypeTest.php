<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\AllocateUserType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class AllocateUserTypeTest extends TypeTestCase
{
 
    public function testAllocateUserSubmitData()
    {
        $formData = array(
            'assignedUnit' => 'unitTest',
        );
     
        $unit = new \HomeOffice\AlfrescoApiBundle\Entity\Unit;
        $team = new \HomeOffice\AlfrescoApiBundle\Entity\Team;
        $team->setAuthorityName('teamTest');
        $unit->setAuthorityName('unitTest');
        $unit->setTeams(array($team));
     
        $ctsListHandler = $this->getListHandlerMock();
     
        $type = new AllocateUserType($ctsListHandler, array(), array());
        $form = $this->factory->create($type);
     
        $factory = new CtsCaseFactory('workspace', 'store', new CTSHelper('security.context', null));
        $ctsCase = $factory->build($formData);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsCase, $factory->build($form->getData()));

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
