<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\FilterQueueType;

class FilterQueueTypeTest extends TypeTestCase
{
 
    public function testFilterTodoQueueSubmitData()
    {
        $formData = array(
            'task' => 'Create case',
            'status' => 'Allocated',
            'correspondenceType' => 'Ministerial'
        );
     
        $type = new FilterQueueType('todoQueue', array(), array());
        $form = $this->factory->create($type);
     
        $form->submit($formData);
     
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
 
    public function testFilterTeamQueueSubmitData()
    {
        $formData = array(
            'task' => 'Create case',
            'status' => 'Allocated',
            'team' => 'testTeam',
            'assignedUser' => 'testUser',
            'correspondenceType' => 'Ministerial'
        );
     
        $type = new FilterQueueType('teamQueue', array('testTeam'), array('testUser'));
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
