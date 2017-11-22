<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseApprovalHelper;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;

/**
 * Class CaseApprovalExtension
 *
 * @package HomeOffice\CtsBundle\Twig
 */
class CaseApprovalExtension extends \Twig_Extension
{
    /**
     * @var CaseApprovalHelper
     */
    private $caseApprovalHelper;

    /**
     * @var CaseProgressHelper
     */
    private $caseProgressHelper;

    /**
     * Constructor
     *
     * @param CaseApprovalHelper $caseApprovalHelper
     * @param CaseProgressHelper $caseProgressHelper
     */
    public function __construct(CaseApprovalHelper $caseApprovalHelper, CaseProgressHelper $caseProgressHelper)
    {
        $this->caseApprovalHelper = $caseApprovalHelper;
        $this->caseProgressHelper = $caseProgressHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'caseIsApproved' => new \Twig_Function_Method($this, 'isApproved'),
        ];
    }

    /**
     * @param string  $task
     * @param CtsCase $case
     *
     * @return bool
     */
    public function isApproved($task, CtsCase $case)
    {
        $steps = $this->caseProgressHelper->getValidSteps($case);

        if (in_array('signOff', $steps) || in_array('dispatch', $steps)) {
            return true;
        }
        return $this->caseApprovalHelper->hasApprovalForTask($task, $case);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'case_approval_extension';
    }
}
