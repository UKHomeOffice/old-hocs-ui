<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;

/**
 * Class CaseProgressHelperTest
 *
 * @package HomeOffice\AlfrescoApiBundle\Tests\Service
 */
class CaseProgressHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Get Valid Steps Returns Array
     */
    public function testGetValidStepsReturnsArray()
    {
        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertTrue(is_array($ctsProgressHelper->getValidSteps(new CtsCase('workspace', 'store'))));
    }

    /**
     * Test Get Valid Steps For New Case
     */
    public function testGetValidStepsForNewCase()
    {
        $expectedValidSteps = ['create'];
        $case = (new CtsCase('workspace', 'store'))->setCaseStatus('New');

        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertEquals($expectedValidSteps, $ctsProgressHelper->getValidSteps($case));
    }

    /**
     * Test Get Valid Steps For Draft Case
     */
    public function testGetValidStepsForDraftCase()
    {
        $expectedValidSteps = ['create', 'draft'];
        $case = (new CtsCase('workspace', 'store'))->setCaseStatus('Draft');

        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertEquals($expectedValidSteps, $ctsProgressHelper->getValidSteps($case));
    }

    /**
     * Test Is Step Valid False
     */
    public function testIsStepValidFalse()
    {
        $case = (new CtsCase('workspace', 'store'))->setCaseStatus('Draft');

        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertFalse($ctsProgressHelper->isStepValid('invalid', $case));
    }

    /**
     * Test Is Step Valid True
     */
    public function testIsStepValidTrue()
    {
        $case = (new CtsCase('workspace', 'store'))->setCaseStatus('Draft');

        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertTrue($ctsProgressHelper->isStepValid('create', $case));
    }

    /**
     * Test Is Step Valid True
     */
    public function testIsMarkupDecisionStepValidTrue()
    {
        $case = (new CtsCase('workspace', 'store'))->setCaseStatus('Completed');

        $case->setMarkupDecision("Refer to OGD");

        $ctsProgressHelper = new CaseProgressHelper();

        $this->assertTrue($ctsProgressHelper->isStepValid('create', $case));
        $this->assertTrue($ctsProgressHelper->isStepValid('approve', $case));
        $this->assertFalse($ctsProgressHelper->isStepValid('dispatch', $case));
    }
}
