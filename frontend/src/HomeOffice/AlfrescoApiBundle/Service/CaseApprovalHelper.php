<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;

/**
 * Class CaseApprovalHelper
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CaseApprovalHelper
{
    private $foiApprovalOrder = [
        'QA Review',
        'Head of unit approval',
        'SCS Approval',
        'Press Office Review',
        'SpAds approval',
        'FOI Minister sign-off',
        'Dispatch'
    ];

    private $approvalOrder = [
        'SCS approval',
        'Perm Sec approval',
        'SpAds approval',
        'Parly approval',
    ];

    /**
     * Has the case been approved for the task?
     *
     * To determine this we compare the key of the task to compare `$task` with the key of the `$case->getCaseTask()`.
     *
     * @param string  $task
     * @param CtsCase $case
     *
     * @return bool
     */
    public function hasApprovalForTask($task, CtsCase $case)
    {
        $approvalOrder = array_map(
            'strtolower',
            in_array($case->getShortName(), ['CtsFoiCase', 'CtsFoiComplaintCase'])
                ? $this->foiApprovalOrder : $this->approvalOrder
        );

        return array_search(strtolower($task), $approvalOrder) <
            array_search(strtolower($case->getCaseTask()), $approvalOrder);
    }
}
