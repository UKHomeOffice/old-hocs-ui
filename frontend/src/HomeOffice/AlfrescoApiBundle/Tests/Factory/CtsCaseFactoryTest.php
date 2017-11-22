<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsCaseFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseFactory('workspace', 'store', new CTSHelper('security.context', null));
    }
 
    private function createCtsCaseStdClass($id, $correspondenceType)
    {
        $case = new \StdClass();
        $case->id = $id;
        $case->correspondenceType = $correspondenceType;
        return $case;
    }

    public function testBuildReturnsACtsDcuMinisterialCase()
    {
        $actual = $this->instance->build(array(), '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase');
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase", $actual);
    }

    public function testBuildReturnsACtsPqCase()
    {
        $actual = $this->instance->build(array(), '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase');
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase", $actual);
    }

    public function testBuildSetsValidParametersDcu()
    {
        $actual = $this->instance->build(
            array('correspondenceType' => "Testing"),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase'
        );
        $this->assertEquals("Testing", $actual->getCorrespondenceType());
    }

    public function testBuildSetsValidParametersPq()
    {
        $actual = $this->instance->build(
            array('correspondenceType' => "Testing"),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase'
        );
        $this->assertEquals("Testing", $actual->getCorrespondenceType());
    }

    public function testBuildIgnoresNoneExistentParametersDcu()
    {
        $actual = $this->instance->build(
            array('correspondenceType' => "Testing", 'fake fake fake' => "Testing"),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase'
        );
        $this->assertEquals("Testing", $actual->getCorrespondenceType());
    }

    public function testBuildIgnoresNoneExistentParametersPq()
    {
        $actual = $this->instance->build(
            array('correspondenceType' => "Testing", 'fake fake fake' => "Testing"),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase'
        );
        $this->assertEquals("Testing", $actual->getCorrespondenceType());
    }
 
    public function testBuildGroupedCases()
    {
        $actual = $this->instance->build(
            array(
                'groupedCases' => array(
                    $this->createCtsCaseStdClass('1234', 'OPQ'),
                    $this->createCtsCaseStdClass('5678', 'OPQ')
                )
            ),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase'
        );
        $groupedCases = $actual->getGroupedCases();
        $this->assertCount(2, $groupedCases);
        $this->assertInstanceOf('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $groupedCases[0]);
        $this->assertInstanceOf('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $groupedCases[1]);
    }
 
    public function testBuildLinkedCases()
    {
        $actual = $this->instance->build(
            array(
                'linkedCases' => array(
                    $this->createCtsCaseStdClass('1234', 'MIN'),
                    $this->createCtsCaseStdClass('5678', 'OPQ')
                )
            ),
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase'
        );
        $linkedCases = $actual->getLinkedCases();
        $this->assertCount(2, $linkedCases);
        $this->assertInstanceOf('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', $linkedCases[0]);
        $this->assertInstanceOf('\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', $linkedCases[1]);
    }
}
