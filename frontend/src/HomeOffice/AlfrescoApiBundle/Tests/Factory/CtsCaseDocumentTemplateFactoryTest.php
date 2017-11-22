<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentTemplateFactory;

class CtsCaseDocumentTemplateFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseMinuteFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseDocumentTemplateFactory('workspace', 'store');
    }

    public function testBuildReturnsACtsCaseDocumentTemplate()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('templateName' => "Testing"));
        $this->assertEquals("Testing", $actual->getTemplateName());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('templateName' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getTemplateName());
    }
}
