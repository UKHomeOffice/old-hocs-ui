<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;


class CTSHelperTest extends ServiceTestHelper
{

    /**
     * @var CTSHelper
     */
    private $instance;
 
    private $tokenStub;
 
    private $securityContext;
 
    private $user;
 
    private $listHandler;
 
    protected function setUp()
    {
        $this->user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $this->user->expects($this->any())
            ->method('getUserName')
            ->will($this->returnValue('Test'));
     
        // @codingStandardsIgnoreStart
        $this->tokenStub = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')             
        // @codingStandardsIgnoreEnd
            ->disableOriginalConstructor()
            ->getMock();
        $this->tokenStub->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($this->user));
     
        $this->securityContext = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->tokenStub));
     
        $this->listHandler = $this->getMockBuilder('HomeOffice\ListBundle\Service\ListHandler')
            ->disableOriginalConstructor()
            ->getMock();
        $this->listHandler->expects($this->any())
            ->method('getList')
            ->will($this->returnValue($this->createUnitWithTeam()));
      
        $this->instance = new CTSHelper($this->securityContext, $this->listHandler);
    }
 
    public function testFormatDateForIsoDate()
    {
        $date = new \DateTime('16-07-2014 09:00');
        $this->assertEquals('2014-07-16T09:00:00+01:00', $this->instance->formatDateToIsoDate($date));
    }
 
    public function testFormatDateForIsoDateNull()
    {
        $this->assertEquals('', $this->instance->formatDateToIsoDate(null));
    }
 
    public function testFormatAtomStringToDate()
    {
        $dateString = '2014-07-16T09:00:00+01:00';
        $this->assertEquals('16/07/14', $this->instance->formatAtomStringToDate($dateString));
    }
 
    public function testFormatAtomStringToDateTime()
    {
        $dateString = '2014-07-16T09:12:01+01:00';
        $this->assertEquals('16/07/14 09:12:01', $this->instance->formatAtomStringToDateTime($dateString));
    }
 
    public function testFormatAtomStringNull()
    {
        $this->assertEquals('', $this->instance->formatAtomStringToDate(null));
        $this->assertEquals('', $this->instance->formatAtomStringToDateTime(null));
    }
 
    public function testFormatDateTimeToDate()
    {
        $dateTime = new \DateTime("now");
        $format = $dateTime->format('d/m/Y');
        $this->assertEquals($format, $this->instance->formatDateTimeToDate($dateTime));
    }
 
    public function testFormatDateTimeToDateNull()
    {
        $this->assertEquals('', $this->instance->formatDateTimeToDate(null));
    }
 
    public function testFormatDateTimeToDateTime()
    {
        $dateTime = new \DateTime("now");
        $format = $dateTime->format('d/m/Y H:i:s');
        $this->assertEquals($format, $this->instance->formatDateTimeToDateTime($dateTime));
    }
 
    public function testFormatDateTimeToDateTimeNull()
    {
        $this->assertEquals('', $this->instance->formatDateTimeToDateTime(null));
    }
 
    public function testFormatBooleanForAtom()
    {
        $this->assertEquals('true', $this->instance->formatBooleanForAtom(true));
        $this->assertEquals('false', $this->instance->formatBooleanForAtom(false));
    }
 
    public function testGetLoggedInUser()
    {
        $this->assertEquals($this->user, $this->instance->getLoggedInUser());
    }
 
    public function testGetLoggedInUserName()
    {
        $this->assertEquals('Test', $this->instance->getLoggedInUserName());
    }
 
    public function testMakeLink()
    {
        $this->assertEquals(
            '<a href=http://www.google.co.uk>http://www.google.co.uk</a>',
            $this->instance->makeLink('http://www.google.co.uk')
        );
    }
 
    public function testMakeLongLink()
    {
        //@codingStandardsIgnoreStart
        $this->assertEquals(
            '<a href=http://www.gooooogle.co.uk/withalonglonglinkoversixtycharacters>http://www.gooooogle.co.uk/withalonglonglinkoversixtychar...</a>',
            $this->instance->makeLink('http://www.gooooogle.co.uk/withalonglonglinkoversixtycharacters')
        );
        //@codingStandardsIgnoreEnd
    }
 
    public function testWordWrap()
    {
        $this->assertEquals(
            "thisisastr\ning",
            $this->instance->wordWrap('thisisastring', 10)
        );
    }
 
    public function testGetCorrespondenceTypeGroup()
    {
        $this->assertEquals('DCU', $this->instance->getCorrespondenceTypeGroup('MIN'));
        $this->assertEquals('DCU', $this->instance->getCorrespondenceTypeGroup('TRO'));
        $this->assertEquals('DCU', $this->instance->getCorrespondenceTypeGroup('DTEN'));
        $this->assertEquals('PQ', $this->instance->getCorrespondenceTypeGroup('NPQ'));
        $this->assertEquals('PQ', $this->instance->getCorrespondenceTypeGroup('LPQ'));
        $this->assertEquals('PQ', $this->instance->getCorrespondenceTypeGroup('OPQ'));
        $this->assertEquals('FOI', $this->instance->getCorrespondenceTypeGroup('FOI'));
        $this->assertEquals('UKVI', $this->instance->getCorrespondenceTypeGroup('IMCB'));
        $this->assertEquals('UKVI', $this->instance->getCorrespondenceTypeGroup('IMCM'));
        $this->assertEquals('UKVI', $this->instance->getCorrespondenceTypeGroup('UTEN'));
        $this->assertEquals('', $this->instance->getCorrespondenceTypeGroup('INVALID'));
    }
 
    public function testGetCaseClassFromType()
    {
        // @codingStandardsIgnoreStart
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', $this->instance->getCaseClassFromType('MIN'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase', $this->instance->getCaseClassFromType('TRO'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', $this->instance->getCaseClassFromType('DTEN'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', $this->instance->getCaseClassFromType('UTEN'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $this->instance->getCaseClassFromType('NPQ'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $this->instance->getCaseClassFromType('LPQ'));
        $this->assertEquals('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $this->instance->getCaseClassFromType('OPQ'));
        // @codingStandardsIgnoreEnd
        $this->assertEquals('', $this->instance->getCaseClassFromType('INVALID'));
    }
 
    public function testGetFormTypeClassFromType()
    {
        // @codingStandardsIgnoreStart
        CtsFeaturesToggle::setSession(new Session(new MockFileSessionStorage()));

        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsDcuMinisterialCaseType', $this->instance->getFormTypeClassFromType('MIN'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsDcuTreatOfficialCaseType', $this->instance->getFormTypeClassFromType('TRO'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsNo10CaseType', $this->instance->getFormTypeClassFromType('DTEN'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsNo10CaseType', $this->instance->getFormTypeClassFromType('UTEN'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsPqCaseType', $this->instance->getFormTypeClassFromType('NPQ'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsPqCaseType', $this->instance->getFormTypeClassFromType('LPQ'));
        $this->assertEquals('\HomeOffice\CtsBundle\Form\Type\CtsPqCaseType', $this->instance->getFormTypeClassFromType('OPQ'));
        // @codingStandardsIgnoreEnd
        $this->assertEquals('', $this->instance->getFormTypeClassFromType('INVALID'));
    }
 
    public function testFormatDateToIsoToEndOfDayDate()
    {
        $date = new DateHelper('16-07-2014 09:00');
        $this->assertEquals('2014-07-17T00:00:00+01:00', $this->instance->formatDateToIsoToEndOfDayDate($date));
     
        $dateString = '16-07-2014 09:00';
        $this->assertEquals('', $this->instance->formatDateToIsoToEndOfDayDate($dateString));
    }
 
    public function testGetUserUnitsTeamsForDisplayNone()
    {
        $units = array();
        $this->assertEquals('None', $this->instance->getUserUnitsTeamsForDisplay($units));
    }
 
    public function testGetUserUnitsTeamsForDisplayUnits()
    {
        $units = array(
            $this->createUnit('UNIT_NAME', 'Unit name'),
            $this->createUnit('ANOTHER_UNIT_NAME', 'Another unit name')
        );
        $this->assertEquals('Unit name, Another unit name', $this->instance->getUserUnitsTeamsForDisplay($units));
    }
 
    public function testGetUserUnitsTeamsForDisplayTeams()
    {
        $teams = array(
            $this->createTeam('TEAM_NAME', 'Team name'),
            $this->createTeam('ANOTHER_TEAM_NAME', 'Another team name')
        );
        $this->assertEquals('Team name, Another team name', $this->instance->getUserUnitsTeamsForDisplay($teams));
    }
 
    public function testmakeFlatMap()
    {
        $result = array('UNIT_NAME' => 'Unit name', 'TEAM_NAME' => 'Team name');
        $this->assertEquals($result, $this->instance->makeFlatMap($this->createUnitWithTeam()));
    }
 
    public function testSetCaseOwnerUnit()
    {
        $ctsCase = new CtsCase('workspace', 'store');
        $ctsCase->setAssignedTeam('UNIT_NAME');
        $this->instance->setCaseOwner($ctsCase);
        $this->assertEquals('Unit name', $ctsCase->getCaseOwner());
    }
 
    public function testSetCaseOwnerTeam()
    {
        $ctsCase = new CtsCase('workspace', 'store');
        $ctsCase->setAssignedTeam('TEAM_NAME');
        $this->instance->setCaseOwner($ctsCase);
        $this->assertEquals('Team name', $ctsCase->getCaseOwner());
    }
 
    public function testSetCaseOwnerUser()
    {
        $ctsCase = new CtsCase('workspace', 'store');
        $ctsCase->setAssignedUser('test');
        $this->instance->setCaseOwner($ctsCase);
        $this->assertEquals('test', $ctsCase->getCaseOwner());
    }
}
