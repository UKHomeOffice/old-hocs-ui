<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase;

/**
 * Class MarkupDecisions
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class MarkupDecisions extends ConstantHelper
{
    const FAQ = 'FAQ';
    const REFER_TO_OGD = 'Refer to OGD';
    const POLICY_RESPONSE = 'Policy response';
    const NO_REPLY_NEEDED = 'No reply needed';
    const REQUEST_UNCLEAR = 'Request unclear';
    const REFER_TO_DCU = 'Refer to DCU';
    const WITHDRAW_QUESTION = 'Withdraw question';
    const PHONE_CALL_RESOLUTION = 'Phone call resolution';
    const ALLOCATE_TO_POLICY_UNIT = 'Allocate to policy unit';
    const ALLOCATE_TO_DRAFT = 'Allocate to draft';
    const SEND_TO_DRAFT = 'Send to draft';
    const RESPOND = 'Respond';
    const WRITTEN_RESPONSE = 'Written response';

    // FOI outcomes
    const INFORMATION_WITHHELD_IN_FULL = 'Information withheld in full';
    const INFORMATION_RELEASED_IN_FULL = 'Information released in full';
    const INFORMATION_RELEASED_IN_PART = 'Information released in part';
    const INFORMATION_NOT_HELD = 'Information not held';
    const REQUEST_VEXATIOUS = 'Request vexatious - S14';
    const REPEAT_REQUEST = 'Repeat request - S14';
    const NEITHER_CONFIRM_ALL = 'Neither confirm nor deny for all info';
    const NEITHER_CONFIRM_SOME = 'Neither confirm nor deny for some info';
    const FEE_THRESHOLD_INVOKED = 'Fee threshold invoked - S12';
    const ALREADY_IN_PUBLIC_DOMAIN_S21 = 'Already in public domain - S21';

    //FOI substantive complaint decisions
    const ORIGINAL_DECISION_UPHELD = 'Original decision upheld';
    const ORIGINAL_DECISION_OVERTURNED = 'Original decision overturned';
    const ORIGINAL_DECISION_UPHELD_IN_PART = 'Original decision upheld in part';
    const INTERNAL_REVIEW_WITHDRAWN = 'Internal review withdrawn';
    const ICO_COMPLAINT_WITHDRAWN = 'ICO complaint withdrawn';

    private static $restrictedMarkupDecisions = array(
        self::FAQ => self::FAQ,
        self::POLICY_RESPONSE => self::POLICY_RESPONSE
    );

    /**
     * Returns an array of all the constants in the class, with values as keys.
     * @return array
     */
    public static function getMarkupDecisionsArray()
    {
        $ary = self::getClassConstants(basename(__FILE__, '.php'));
        asort($ary);
        return $ary;
    }

    /**
     * These markup decisions will all progress the user to the next transition with the same default options.
     * They need to be identified as similar as we only want to import the shared template once.
     *
     */
    public static function getNextMarkupDecisions()
    {
        return [
            self::ALLOCATE_TO_DRAFT,
            self::ALLOCATE_TO_POLICY_UNIT,
            self::SEND_TO_DRAFT,
            self::FAQ,
            self::POLICY_RESPONSE,

        ];
    }

    /**
     *
     * @param string $caseTask
     * @param array $fullDecisionArray
     * @param array $restrictedDecisionArray
     * @return array
     */
    private static function processDecisionList($caseTask, $fullDecisionArray, $restrictedDecisionArray)
    {
        switch ($caseTask) {
            case TaskStatus::CREATE_CASE:
            case TaskStatus::AMEND_CASE:
            case TaskStatus::QA_CASE:
            case TaskStatus::DRAFT_RESPONSE:
            case TaskStatus::AMEND_RESPONSE:
                if ($fullDecisionArray != null) {
                    asort($fullDecisionArray);
                }
                return $fullDecisionArray;
            default:
                if ($restrictedDecisionArray != null) {
                    asort($restrictedDecisionArray);
                }
                return $restrictedDecisionArray;
        }
    }

    /**
     * Return the standard markup decision list
     * @param TaskStatus $caseTask
     * @return array
     */
    public static function getStandardMarkupDecisionList($caseTask)
    {
        $standardMarkupDecisions = array(
            self::FAQ => self::FAQ,
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::POLICY_RESPONSE => self::POLICY_RESPONSE,
            self::REFER_TO_OGD => self::REFER_TO_OGD);

        return self::processDecisionList($caseTask, $standardMarkupDecisions, self::$restrictedMarkupDecisions);
    }

    /**
     * Return the FOI markup decision list
     * @param TaskStatus $caseTask
     * @param boolean $isEir
     * @return array
     */
    public static function getFoiDecisionList($caseTask, $isEir)
    {
        $foiMarkupDecisions = array(
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::REFER_TO_DCU => self::REFER_TO_DCU);

        if ($isEir) {
            $standardDecisions = self::getFoiEirOutcomesArray($foiMarkupDecisions);
            $restrictedDecisions = self::getFoiEirOutcomesArray(null);
        } else {
            $standardDecisions = self::getFoiOutcomesArray($foiMarkupDecisions);
            $restrictedDecisions = self::getFoiOutcomesArray(null);
        }
        return self::processDecisionList($caseTask, $standardDecisions, $restrictedDecisions);
    }

    /**
     * Return the FOI markup decision list
     *
     * @param CtsCase $case
     * @param bool    $isEir
     *
     * @return array
     */
    public static function getGuftFoiDecisionList(CtsCase $case, $isEir)
    {
        $foiMarkupDecisions = [
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::REFER_TO_DCU => self::REFER_TO_DCU
        ];

        if (in_array($case->getCaseTask(), [TaskStatus::CREATE_CASE, TaskStatus::MISALLOCATED])) {
            return [self::ALLOCATE_TO_DRAFT => self::ALLOCATE_TO_DRAFT] + $foiMarkupDecisions;
        }

        if (in_array($case->getCorrespondenceType(), ['FSC', 'FSCI'])) {
            return self::getFoiSubstantiveComplaintDecisions($case->getCorrespondenceType());
        }

        if ($isEir) {
            $standardDecisions = self::getFoiEirOutcomesArray($foiMarkupDecisions);
            $restrictedDecisions = self::getFoiEirOutcomesArray(null);
        } else {
            $standardDecisions = self::getFoiOutcomesArray($foiMarkupDecisions);
            $restrictedDecisions = self::getFoiOutcomesArray(null);
        }

        return self::processDecisionList($case->getCaseTask(), $standardDecisions, $restrictedDecisions);
    }

    /**
     * Returns an array of all the constants required for FOI outcomes.
     * @param array $outcomesArray
     * @return array
     */
    private static function getFoiOutcomesArray($outcomesArray)
    {
        $outcomesArray[self::INFORMATION_WITHHELD_IN_FULL] = self::INFORMATION_WITHHELD_IN_FULL;
        $outcomesArray[self::INFORMATION_RELEASED_IN_FULL] = self::INFORMATION_RELEASED_IN_FULL;
        $outcomesArray[self::INFORMATION_RELEASED_IN_PART] = self::INFORMATION_RELEASED_IN_PART;
        $outcomesArray[self::INFORMATION_NOT_HELD] = self::INFORMATION_NOT_HELD;
        $outcomesArray[self::REQUEST_VEXATIOUS] = self::REQUEST_VEXATIOUS;
        $outcomesArray[self::REPEAT_REQUEST] = self::REPEAT_REQUEST;
        $outcomesArray[self::REQUEST_UNCLEAR] = self::REQUEST_UNCLEAR;
        $outcomesArray[self::NEITHER_CONFIRM_ALL] = self::NEITHER_CONFIRM_ALL;
        $outcomesArray[self::NEITHER_CONFIRM_SOME] = self::NEITHER_CONFIRM_SOME;
        $outcomesArray[self::FEE_THRESHOLD_INVOKED] = self::FEE_THRESHOLD_INVOKED;
        $outcomesArray[self::ALREADY_IN_PUBLIC_DOMAIN_S21] = self::ALREADY_IN_PUBLIC_DOMAIN_S21;
        return $outcomesArray;
    }

    /**
     * Returns an array of all the constants required for FOI EIR outcomes.
     * @param array $outcomesArray
     * @return array
     */
    private static function getFoiEirOutcomesArray($outcomesArray)
    {
        $outcomesArray[self::INFORMATION_WITHHELD_IN_FULL] = self::INFORMATION_WITHHELD_IN_FULL;
        $outcomesArray[self::INFORMATION_RELEASED_IN_FULL] = self::INFORMATION_RELEASED_IN_FULL;
        $outcomesArray[self::INFORMATION_RELEASED_IN_PART] = self::INFORMATION_RELEASED_IN_PART;
        $outcomesArray[self::NEITHER_CONFIRM_ALL] = self::NEITHER_CONFIRM_ALL;
        $outcomesArray[self::NEITHER_CONFIRM_SOME] = self::NEITHER_CONFIRM_SOME;
        return $outcomesArray;
    }

    /**
     * @return array
     */
    private static function getFoiSubstantiveComplaintDecisions($caseType)
    {
        $decisions = [
            self::ORIGINAL_DECISION_UPHELD => self::ORIGINAL_DECISION_UPHELD,
            self::ORIGINAL_DECISION_OVERTURNED => self::ORIGINAL_DECISION_OVERTURNED,
            self::ORIGINAL_DECISION_UPHELD_IN_PART => self::ORIGINAL_DECISION_UPHELD_IN_PART,
        ];

        if ($caseType == 'FSC') {
            $decisions[self::INTERNAL_REVIEW_WITHDRAWN] = self::INTERNAL_REVIEW_WITHDRAWN;
        } else {
            $decisions[self::ICO_COMPLAINT_WITHDRAWN] = self::ICO_COMPLAINT_WITHDRAWN;
        }

        return $decisions;
    }

    /**
     * Return the PQ markup decision list
     * @param TaskStatus $caseTask
     * @return array
     *
     * @deprecated Removed once Guft is live.
     */
    public static function getPqDecisionList($caseTask)
    {
        $pqMarkupDecisions = array(
            self::REFER_TO_OGD => self::REFER_TO_OGD,
            self::WITHDRAW_QUESTION => self::WITHDRAW_QUESTION,
            self::ALLOCATE_TO_POLICY_UNIT => self::ALLOCATE_TO_POLICY_UNIT);

        $restrictedMarkupDecisions = [
            self::ALLOCATE_TO_POLICY_UNIT => self::ALLOCATE_TO_POLICY_UNIT
        ];

        return self::processDecisionList($caseTask, $pqMarkupDecisions, $restrictedMarkupDecisions);
    }

    /**
     * Return the Guft PQ markup decision list
     *
     * @param CtsCase $case
     *
     * @return array
     */
    public static function getGuftDecisionList(CtsCase $case)
    {
        switch ($case->getShortName()) {
            case 'CtsPqCase':
                return [
                    self::ALLOCATE_TO_POLICY_UNIT => 'Allocate to draft',
                    self::WITHDRAW_QUESTION => self::NO_REPLY_NEEDED,
                    self::REFER_TO_OGD => self::REFER_TO_OGD,
                ];
            case 'CtsUkviCase':
                return [
                    self::ALLOCATE_TO_POLICY_UNIT => 'Allocate to draft',
                    self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                    self::REFER_TO_OGD => self::REFER_TO_OGD,
                ];
            case 'CtsNo10Case':
                if ($case->getCorrespondenceType() === 'DTEN') {
                    return [
                        self::ALLOCATE_TO_POLICY_UNIT => 'Allocate to draft',
                        self::FAQ => self::FAQ,
                        self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                        self::POLICY_RESPONSE => self::POLICY_RESPONSE,
                        self::REFER_TO_OGD => self::REFER_TO_OGD
                    ];
                } else {
                    return [
                        self::ALLOCATE_TO_POLICY_UNIT => 'Allocate to draft',
                        self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                        self::REFER_TO_OGD => self::REFER_TO_OGD,
                    ];
                }
            case 'CtsDcuMinisterialCase':
                return [
                    self::FAQ => self::FAQ,
                    self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                    self::POLICY_RESPONSE => self::POLICY_RESPONSE,
                    self::REFER_TO_OGD => self::REFER_TO_OGD,
                ];
            case 'CtsDcuTreatOfficialCase':
                return [
                    self::FAQ => self::FAQ,
                    self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                    self::POLICY_RESPONSE => self::POLICY_RESPONSE,
                    self::REFER_TO_OGD => self::REFER_TO_OGD,
                ];
            case 'CtsFoiCase':
            case 'CtsFoiComplaintCase':
                /** @var CtsFoiCase $case */
                return self::getGuftFoiDecisionList($case, $case->getFoiIsEir());
            case 'CtsHmpoCorCase':
                return self::getHmpoCorrespondenceDecisions($case);
            default:
                return [];

        }
    }

    /**
     * Return the Guft PQ markup decision list
     *
     * @return array
     */
    public static function getGuftPqDecisionList()
    {
        return [
            self::ALLOCATE_TO_POLICY_UNIT => 'Allocate to draft',
            self::WITHDRAW_QUESTION => 'No reply needed',
            self::REFER_TO_OGD => self::REFER_TO_OGD,
        ];
    }

    /**
     * Get HMPO Correspondence Decisions
     *
     * @param CtsCase $case
     *
     * @return array
     */
    public static function getHmpoCorrespondenceDecisions(CtsCase $case)
    {
        if ($case->getCaseStatus() === CaseStatus::DRAFT) {
            return [
                self::WRITTEN_RESPONSE => self::WRITTEN_RESPONSE,
                self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
                self::REFER_TO_OGD    => self::REFER_TO_OGD,
            ];
        }

        return [
            self::SEND_TO_DRAFT   => self::SEND_TO_DRAFT,
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::REFER_TO_OGD    => self::REFER_TO_OGD,
        ];
    }

    /**
     * Return the HMPO markup decision list
     * @param TaskStatus $caseTask
     * @return array
     */
    public static function getHmpoDecisionList($caseTask)
    {
        $hmpoMarkupDecisions = array(
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::PHONE_CALL_RESOLUTION => self::PHONE_CALL_RESOLUTION,
            self::REFER_TO_OGD => self::REFER_TO_OGD,
            self::WRITTEN_RESPONSE => self::WRITTEN_RESPONSE);

        $restrictedMarkupDecisions = array(self::WRITTEN_RESPONSE => self::WRITTEN_RESPONSE);

        return self::processDecisionList($caseTask, $hmpoMarkupDecisions, $restrictedMarkupDecisions);
    }

    /**
     * Return the FOI time complaint markup decision list
     * @param TaskStatus $caseTask
     * @return array
     */
    public static function getFoiTimeComplaintDecisionList($caseTask)
    {
        $hmpoMarkupDecisions = array(
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::RESPOND => self::RESPOND,
            self::REFER_TO_DCU => self::REFER_TO_DCU);

        $restrictedDecisions = array(self::RESPOND => self::RESPOND);

        return self::processDecisionList($caseTask, $hmpoMarkupDecisions, $restrictedDecisions);
    }

    /**
     * Return the FOI substantive complaint and tribunal markup decision list
     * @param TaskStatus $caseTask
     * @return array
     */
    public static function getFoiSubstantiveComplaintAndTribunalDecisionList($caseTask)
    {
        $foiMarkupDecisions = array(
            self::NO_REPLY_NEEDED => self::NO_REPLY_NEEDED,
            self::REFER_TO_DCU => self::REFER_TO_DCU);

        $standardDecisions = self::getFoiSubstantiveComplaintAndTribunalStandardArray($foiMarkupDecisions);
        $restrictedDecisions = self::getFoiSubstantiveComplaintAndTribunalStandardArray(null);

        return self::processDecisionList($caseTask, $standardDecisions, $restrictedDecisions);
    }

    /**
     * List of decisions for FOI substantive complaint and FOI tribunal
     * @param array $markupArray
     * @return array
     */
    private static function getFoiSubstantiveComplaintAndTribunalStandardArray($markupArray)
    {
        $markupArray[self::HO_OVERTURNED] = self::HO_OVERTURNED;
        $markupArray[self::HO_UPHELD] = self::HO_UPHELD;
        $markupArray[self::HO_UPHELD_IN_PART] = self::HO_UPHELD_IN_PART;
        $markupArray[self::INFORMALLY_RESOLVED] = self::INFORMALLY_RESOLVED;

        return $markupArray;
    }

    /**
     * Returns an array of all the constants in the class, with values as keys restriciting based
     * on the case task and type.
     *
     * @param CtsCase $ctsCase
     * @return array
     */
    public static function getMarkupDecisionsArrayForWorkflowStage($ctsCase)
    {
        $caseTask = $ctsCase->getCaseTask();
        switch ($ctsCase->getCorrespondenceType()) {
            case 'MIN':
            case 'TRO':
            case 'IMCB':
            case 'IMCM':
                return self::getStandardMarkupDecisionList($caseTask);
            case 'FOI':
                return self::getFoiDecisionList($caseTask, $ctsCase->getFoiIsEir());
            case 'NPQ':
            case 'LPQ':
            case 'OPQ':
                return self::getPqDecisionList($caseTask);
            case 'COM':
            case 'GEN':
            case 'COL':
                return self::getHmpoDecisionList($caseTask);
            case 'FTC':
            case 'FTCI':
                return self::getFoiTimeComplaintDecisionList($caseTask);
            case 'FSC':
            case 'FSCI':
            case 'FLT':
            case 'FUT':
                return self::getFoiSubstantiveComplaintAndTribunalDecisionList($caseTask);
            case 'DTEN':
            case 'UTEN':
            default:
                return self::$restrictedMarkupDecisions;
        }
    }
}
