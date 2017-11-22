<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCollCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCorCase;

/**
 * Class CaseProgressHelper
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CaseProgressHelper
{
    const PROGRESS_CREATE    = 'New';
    const PROGRESS_DRAFT     = 'Draft';
    const PROGRESS_APPROVE   = 'Approvals';
    const PROGRESS_NFA       = 'NFA';
    const PROGRESS_OGD       = 'OGD';
    const PROGRESS_SIGNOFF   = 'Obtain sign-off';
    const PROGRESS_DISPATCH  = 'Dispatch';
    const PROGRESS_COMPLETED = 'Completed';
    const PROGRESS_HOLD      = 'Hold';

    /**
     * @var array
     */
    protected $defaultSteps = ['create', 'draft', 'approve', 'signOff', 'dispatch'];

    /**
     * Get the current progress stage for a case
     *
     * @param CtsCase|CtsHmpoCorCase|CtsHmpoCollCase $case
     *
     * @return array
     */
    public function getValidSteps(CtsCase $case)
    {
        if ($case->getCaseStatus() == self::PROGRESS_CREATE) {
            return ['create'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_DRAFT) {
            return ['create', 'draft'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_NFA ||
            $case->getMarkupDecision() === MarkupDecisions::NO_REPLY_NEEDED
        ) {
            return ['create', 'approve'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_OGD ||
            $case->getMarkupDecision() === MarkupDecisions::REFER_TO_OGD
        ) {
            return ['create', 'approve'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_APPROVE ||
            ($case->getShortName() === 'CtsFoiCase' && $case->getCaseStatus() === self::PROGRESS_SIGNOFF)
        ) {
            return ['create', 'draft', 'approve'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_HOLD && $case->getCorrespondenceType() === 'FSCI') {
            return ['create', 'draft', 'dispatch'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_NFA && $case->getCorrespondenceType() === 'FSCI') {
            return ['create', 'draft', 'approve'];
        }

        if ($case->getCaseStatus() == self::PROGRESS_SIGNOFF) {
            return ['create', 'draft', 'approve', 'signOff'];
        }

        if (in_array($case->getCorrespondenceType(), ['MIN', 'TRO']) &&
            $case->getCaseStatus() == self::PROGRESS_COMPLETED &&
            $case->getHmpoResponse() == 'Phone'
        ) {
            // A DCU MIN|TRO Case has been responded to and closed by phone.
            return ['create', 'draft'];
        }

        if ($case->getCorrespondenceType() === 'COL' &&
            $case->getCaseStatus() == self::PROGRESS_COMPLETED &&
            is_null($case->getDeliveryNumber())
        ) {
            // A HMPO Collective case was cancelled
            return ['create', 'draft'];
        }

        if ($case instanceof CtsHmpoCorCase &&
            $case->getHmpoResponse() === 'Phone' &&
            $case->getCaseStatus() == self::PROGRESS_COMPLETED
        ) {
            // A HMPO Complaint case was resolved by phone
            return ['create', 'draft', 'dispatch'];
        }

        if (in_array($case->getCaseStatus(), [self::PROGRESS_DISPATCH, self::PROGRESS_COMPLETED])) {
            return ['create', 'draft', 'approve', 'signOff', 'dispatch'];
        }

        return [];
    }

    /**
     * Is the step valid?
     *
     * @param string  $step
     * @param CtsCase $case
     *
     * @return bool
     */
    public function isStepValid($step, CtsCase $case)
    {
        return in_array($step, $this->getValidSteps($case));
    }

    /**
     * Does the step exists for the case?
     *
     * @param string  $step
     * @param CtsCase $case
     *
     * @return array
     */
    public function hasStep($step, CtsCase $case)
    {
        $steps = $this->defaultSteps;

        if (
            in_array($case->getCorrespondenceType(), ['IMCB', 'COL']) ||
            in_array(get_class($case), [
                CtsFoiCase::class,
                CtsFoiComplaintCase::class,
                CtsDcuTreatOfficialCase::class,
                CtsHmpoCorCase::class,
            ])
        ) {
            $steps = array_diff($steps, ['signOff']);
        }

        if ($case->getCorrespondenceType() === 'FSCI') {
            if ($case->getCaseStatus() === self::PROGRESS_NFA) {
                $steps = array_diff($steps, ['dispatch']);
            } else {
                $steps = array_diff($steps, ['approve']);
            }
        }

        return in_array($step, $steps);
    }
}
