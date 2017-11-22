<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\PermissionsFactory;

class PermissionsFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PermissionsFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new PermissionsFactory();
    }

    public function testBuildReturnsACasesPermissions()
    {
        $actual = $this->instance->build(array(), "HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions");

        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions", $actual);
    }
 
    public function testBuildReturnsADocumentTemplatesPermissions()
    {
        $actual = $this->instance->build(array(), "HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions");

        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions", $actual);
    }

    public function testBuildSetsValidParametersCasesPermissions()
    {
        $actual = $this->instance->build(
            array('canCreateFolder' => "true"),
            "HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions"
        );

        $this->assertEquals("true", $actual->getCanCreateFolder());
    }
 
    public function testBuildSetsValidParametersDocumentTemplates()
    {
        $actual = $this->instance->build(
            array('canCreateFolder' => "true"),
            "HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions"
        );

        $this->assertEquals("true", $actual->getCanCreateFolder());
    }

    public function testBuildIgnoresNoneExistentParametersCasesPermissions()
    {
        $actual = $this->instance->build(
            array('canCreateFolder' => "false", 'fake fake fake' => "Testing"),
            "HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions"
        );

        $this->assertEquals("false", $actual->getCanCreateFolder());
    }
 
    public function testBuildIgnoresNoneExistentParametersDocumentTemplates()
    {
        $actual = $this->instance->build(
            array('canCreateFolder' => "false", 'fake fake fake' => "Testing"),
            "HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions"
        );

        $this->assertEquals("false", $actual->getCanCreateFolder());
    }
}
