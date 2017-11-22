<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\BulkDocumentFactory;

class BulkDocumentFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BulkDocumentFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new BulkDocumentFactory('workspace', 'store');
    }

    public function testBuildReturnsABulkDocument()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\BulkDocument", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('autoCreateFailureMessage' => "Testing"));
        $this->assertEquals("Testing", $actual->getAutoCreateFailureMessage());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('autoCreateFailureMessage' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getAutoCreateFailureMessage());
    }
}
