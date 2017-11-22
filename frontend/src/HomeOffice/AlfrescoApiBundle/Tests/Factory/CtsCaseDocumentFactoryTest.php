<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentFactory;

class CtsCaseDocumentFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseDocumentFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseDocumentFactory('workspace', 'store');
    }

    public function testBuildReturnsACtsCaseDocument()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('documentType' => "Testing"));
        $this->assertEquals("Testing", $actual->getDocumentType());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('documentType' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getDocumentType());
    }
}
