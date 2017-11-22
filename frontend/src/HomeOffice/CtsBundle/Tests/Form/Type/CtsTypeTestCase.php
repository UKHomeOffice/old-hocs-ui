<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Member;

class CtsTypeTestCase extends TypeTestCase
{
 
    public function getListHandlerMock()
    {
        $mock = $this->getMockBuilder('HomeOffice\ListBundle\Service\ListHandler', array('getList'))
            ->disableOriginalConstructor()
            ->getMock();
        $mock
            ->method('getList')
            ->with($this->logicalOr(
                $this->equalTo('ctsMemberList'),
                $this->equalTo('ctsTopicList'),
                $this->equalTo('ctsUnitAndTeamList'),
                $this->equalTo('ctsMinisterList'),
                $this->equalTo('ctsUnitList'),
                $this->equalTo('ctsDecisionList')
            ))
            ->will($this->returnCallback(array($this, 'handleGetListMockCallback')));

        return $mock;
    }
 
    public function handleGetListMockCallback($list)
    {
        $member = ['Dennis Skinner', 'D Skinner', 'Party', 'London', '1234', 'TRUE', 'FALSE'];
        switch ($list) {
            case 'ctsMemberList':
                return array("Dennis Skinner" => new Member($member));
            case 'ctsMinisterList':
                return array("Minister 1" => "Minister 1", "Minister 2" => "Minister 2");
            case 'ctsTopicList':
                return array("UNIT 1" => array("Every" => "Every"), "UNIT 2" => array("Hazelenut" => "Hazelenut"));
            case 'ctsUnitAndTeamList':
                $unit = new Unit();
                $team = new Team();
                $team->setAuthorityName('teamTest');
                $unit->setAuthorityName('unitTest');
                $unit->setTeams(array($team));
                return array($unit);
            case 'ctsUnitList':
                return array("UNIT 1" => "Unit 1", "UNIT 2" => "Unit 2");
            case 'ctsDecisionList':
                return array("FAQ" => "FAQ", "Policy response" => "Policy Response");
        }
    }
}
