<?php

namespace HomeOffice\CtsBundle\Tests\Twig;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use HomeOffice\CtsBundle\Twig\CaseProgressExtension;
use PHPUnit_Framework_MockObject_MockObject as Mock;
/**
 * Class CaseProgressExtensionTest
 *
 * @package HomeOffice\CtsBundle\Tests\Twig
 */
class CaseProgressExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CaseProgressHelper|Mock
     */
    private $caseProgressHelper;

    /**
     * Test Case Progress Class Returns Disabled Class
     */
    public function testCaseProgressClassReturnsDisabledClass()
    {
        $case = new CtsCase('workspace', 'store');
        $this->caseProgressHelper
            ->expects($this->once())
            ->method('getValidSteps')
            ->with($case)
            ->willReturn([]);

        $caseProgressExtension = new CaseProgressExtension($this->caseProgressHelper);

        $this->assertSame(
            'class=disabled',
            $caseProgressExtension->getProgressClass($case, 'activeStep', 'currentStep')
        );
    }

    /**
     * Test Case Progress Class Returns Active Class
     */
    public function testCaseProgressClassReturnsActiveClass()
    {
        $case = new CtsCase('workspace', 'store');
        $this->caseProgressHelper
            ->expects($this->once())
            ->method('getValidSteps')
            ->with($case)
            ->willReturn(['activeStep']);

        $caseProgressExtension = new CaseProgressExtension($this->caseProgressHelper);

        $this->assertSame(
            'class=active',
            $caseProgressExtension->getProgressClass($case, 'activeStep', 'activeStep')
        );
    }

    /**
     * Test Case Progress Class Returns Active Class
     */
    public function testCaseProgressClassReturnsEmptyClass()
    {
        $case = new CtsCase('workspace', 'store');
        $this->caseProgressHelper
            ->expects($this->once())
            ->method('getValidSteps')
            ->with($case)
            ->willReturn(['currentStep']);

        $caseProgressExtension = new CaseProgressExtension($this->caseProgressHelper);

        $this->assertSame(
            '',
            $caseProgressExtension->getProgressClass($case, 'activeStep', 'currentStep')
        );
    }

    /**
     * Set Up
     */
    protected function setUp()
    {
        $this->caseProgressHelper = $this->getMockBuilder(CaseProgressHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
