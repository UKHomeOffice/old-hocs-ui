<?php

namespace HomeOffice\AlfrescoApiBundle\Factory\Polyfill;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory as OriginalCtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;

/**
 * Class CtsCaseFactory
 *
 * @package HomeOffice\AlfrescoApiBundle\Factory\Polyfill
 *
 * @deprecated This polyfill is in place to support backend pending changes. Once the backend has been updated the
 * polyfill should be deprecated in favour of HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper
 */
class CtsCaseFactory extends OriginalCtsCaseFactory
{
    /**
     * @inheritdoc
     */
    protected function buildCtsCase($parameters, $ctsCase, $type)
    {
        $case = parent::buildCtsCase($parameters, $ctsCase, $type);

        if (
            in_array(
                $case->getShortName(), ['CtsUkviCase', 'CtsNo10Case', 'CtsDcuMinisterialCase',
                    'CtsDcuTreatOfficialCase', 'CtsFoiCase', 'CtsFoiComplaintCase']
            )
        ) {
            if ($case->getCaseStatus() === 'Draft' && in_array($case->getCaseTask(), ['QA review', 'QA Review'])) {
                // When a Ukvi case is in Draft and the task is `QA Review` the case status should be Approvals
                $case->setCaseStatus(CaseProgressHelper::PROGRESS_APPROVE);
            }

            if ($case->getCaseStatus() === CaseProgressHelper::PROGRESS_SIGNOFF &&
                in_array(
                    $case->getCorrespondenceType(),
                    CaseCorrespondenceType::getSubtypesFromCaseType(CaseCorrespondenceSubType::getCaseType($case->getCorrespondenceType()))
                )
            ) {
                $case->setCaseStatus(CaseProgressHelper::PROGRESS_APPROVE);
            }

            if ($case->getCaseStatus() === 'Approvals' &&
                in_array($case->getCaseTask(), ['Minister\'s sign-off', 'Home Sec\'s sign-off', 'Private Office approval', 'HS Private Office approval'])
            ) {
                $case->setCaseStatus(CaseProgressHelper::PROGRESS_SIGNOFF);
            }
        }

        return $case;
    }
}
