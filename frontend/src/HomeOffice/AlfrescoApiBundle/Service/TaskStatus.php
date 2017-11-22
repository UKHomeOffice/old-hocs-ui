<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class TaskStatus
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
abstract class TaskStatus extends ConstantHelper
{
    // Create
    const CREATE_CASE  = 'Create case';
    const MISALLOCATED = 'Misallocated';
    const QA_CASE      = 'QA case';
    const MARK_UP      = 'Mark up';
    const AMEND_CASE   = 'Amend case'; // Not known by Alfreso

    // Draft
    const DRAFT_RESPONSE  = 'Draft response';
    const AMEND_RESPONSE  = 'Amend response';
    const QA_REVIEW       = 'QA review';
    const DRAFT_AND_CLEAR = 'Draft and Clear';
    const DRAFT_DEFER     = 'Defer';

    // Approvals
    const SCS_APPROVAL               = 'SCS approval';
    const PERM_SEC_APPROVAL          = 'Perm Sec approval';
    const SPADS_APPROVAL             = 'SpAds approval';
    const CHECK_AND_PRINT            = 'Check and print';
    const CHECK_AND_BUFF_PRINT       = 'Check and buff print';
    const PARLY_APPROVAL             = 'Parly approval';
    const PRIVATE_OFFICE_APPROVAL    = 'Private Office approval';
    const PRESS_OFFICE_REVIEW        = 'Press Office review';
    const HS_PRIVATE_OFFICE_APPROVAL = 'HS Private Office approval';

    // Sign off
    const FOI_MINISTER_SIGN_OFF                  = 'FOI Minister sign-off';
    const MINISTERS_SIGN_OFF                     = "Minister's sign-off";
    const PRINT_RUN                              = 'Print run';
    const HOME_SECS_SIGN_OFF                     = "Home Sec's sign-off";
    const BUFF_PRINT_RUN                         = 'Buff print run';
    const LORD_MINISTERS_SIGN_OFF                = "Lords Minister's sign-off";
    const PARLIAMENTARY_UNDER_SECRETARY_SIGN_OFF = 'Parliamentary Under Secretary sign-off';

    // Hold
    const ICO_OR_TRIBUNAL_OUTCOME = 'ICO or tribunal outcome';

    // Dispatch
    const DISPATCH_RESPONSE = 'Dispatch response';
    const PARLY             = 'Parly';
    const ANSWERED          = 'Answered';

    // Transfer
    const TRANSFER = 'Transfer'; // Not known by Alfreso

    /**
     * Returns an array of all the constants in the class, with values as keys.
     * @return array
     */
    public static function getTaskArray()
    {
        $ary = self::getClassConstants(basename(__FILE__, '.php'));
        asort($ary);

        return $ary;
    }

    /**
     * @param string $task
     *
     * @return string
     */
    public static function getStatusForTask($task)
    {
        switch (stripcslashes($task)) {
            case self::CREATE_CASE:
            case self::MISALLOCATED:
            case self::QA_CASE:
            case self::MARK_UP:
                return CaseStatus::NEW_CASE;

            case self::DRAFT_RESPONSE:
            case self::AMEND_RESPONSE:
            case self::QA_REVIEW:
            case self::DRAFT_AND_CLEAR:
            case self::DRAFT_DEFER:
                return CaseStatus::DRAFT;

            case self::SCS_APPROVAL:
            case self::PERM_SEC_APPROVAL:
            case self::SPADS_APPROVAL:
            case self::CHECK_AND_PRINT:
            case self::CHECK_AND_BUFF_PRINT:
            case self::PARLY_APPROVAL:
            case self::PRIVATE_OFFICE_APPROVAL:
            case self::PRESS_OFFICE_REVIEW:
            case self::HS_PRIVATE_OFFICE_APPROVAL:
                return CaseStatus::APPROVALS;

            case self::FOI_MINISTER_SIGN_OFF:
            case self::MINISTERS_SIGN_OFF:
            case self::PRINT_RUN:
            case self::HOME_SECS_SIGN_OFF:
            case self::BUFF_PRINT_RUN:
            case self::LORD_MINISTERS_SIGN_OFF:
            case self::PARLIAMENTARY_UNDER_SECRETARY_SIGN_OFF:
                return CaseStatus::OBTAIN_SIGNOFF;

            case self::ICO_OR_TRIBUNAL_OUTCOME:
                return CaseStatus::HOLD;

            case self::DISPATCH_RESPONSE:
            case self::PARLY:
            case self::ANSWERED:
                return CaseStatus::DISPATCH;

            // Transfer
            case self::TRANSFER:
                return CaseStatus::OGD;

            default:
                return '';
        }
    }

    /**
     * Returns an array of all the constants in the class, with names as keys.
     * @return array
     */
    public static function getTaskArrayConstants()
    {
        $refl = new \ReflectionClass('\HomeOffice\AlfrescoApiBundle\Service\TaskStatus');
        return $refl->getConstants();
    }

    /**
     * Returns an array of all the constants for filtering.
     *
     * @return array
     */
    public static function getTasksForFilterArray()
    {
        $taskArray = [
            // New
            TaskStatus::CREATE_CASE                            => TaskStatus::CREATE_CASE,
            TaskStatus::MISALLOCATED                           => TaskStatus::MISALLOCATED,
            TaskStatus::AMEND_CASE                             => TaskStatus::AMEND_CASE,
            TaskStatus::MARK_UP                                => TaskStatus::MARK_UP,
            TaskStatus::QA_CASE                                => TaskStatus::QA_CASE,

            // Draft
            TaskStatus::DRAFT_RESPONSE                         => TaskStatus::DRAFT_RESPONSE,
            TaskStatus::AMEND_RESPONSE                         => TaskStatus::AMEND_RESPONSE,
            TaskStatus::QA_REVIEW                              => TaskStatus::QA_REVIEW,
            TaskStatus::DRAFT_AND_CLEAR                        => TaskStatus::DRAFT_AND_CLEAR,
            TaskStatus::DRAFT_DEFER                            => TaskStatus::DRAFT_DEFER,

            // Approvals
            TaskStatus::SCS_APPROVAL                           => TaskStatus::SCS_APPROVAL,
            TaskStatus::PERM_SEC_APPROVAL                      => TaskStatus::PERM_SEC_APPROVAL,
            TaskStatus::SPADS_APPROVAL                         => TaskStatus::SPADS_APPROVAL,
            TaskStatus::CHECK_AND_PRINT                        => TaskStatus::CHECK_AND_PRINT,
            TaskStatus::CHECK_AND_BUFF_PRINT                   => TaskStatus::CHECK_AND_BUFF_PRINT,
            TaskStatus::PARLY_APPROVAL                         => TaskStatus::PARLY_APPROVAL,
            TaskStatus::PRIVATE_OFFICE_APPROVAL                => TaskStatus::PRIVATE_OFFICE_APPROVAL,
            TaskStatus::PRESS_OFFICE_REVIEW                    => TaskStatus::PRESS_OFFICE_REVIEW,
            TaskStatus::HS_PRIVATE_OFFICE_APPROVAL             => TaskStatus::HS_PRIVATE_OFFICE_APPROVAL,

            // Signoff
            TaskStatus::FOI_MINISTER_SIGN_OFF                  => TaskStatus::FOI_MINISTER_SIGN_OFF,
            TaskStatus::MINISTERS_SIGN_OFF                     => TaskStatus::MINISTERS_SIGN_OFF,
            TaskStatus::PRINT_RUN                              => TaskStatus::PRINT_RUN,
            TaskStatus::HOME_SECS_SIGN_OFF                     => TaskStatus::HOME_SECS_SIGN_OFF,
            TaskStatus::BUFF_PRINT_RUN                         => TaskStatus::BUFF_PRINT_RUN,
            TaskStatus::LORD_MINISTERS_SIGN_OFF                => TaskStatus::LORD_MINISTERS_SIGN_OFF,
            TaskStatus::PARLIAMENTARY_UNDER_SECRETARY_SIGN_OFF => TaskStatus::PARLIAMENTARY_UNDER_SECRETARY_SIGN_OFF,

            // Dispatch
            TaskStatus::DISPATCH_RESPONSE                      => TaskStatus::DISPATCH_RESPONSE,
            TaskStatus::PARLY                                  => TaskStatus::PARLY,
            TaskStatus::ANSWERED                               => TaskStatus::ANSWERED,

            // Transfer
            TaskStatus::TRANSFER                               => TaskStatus::TRANSFER,

            // Hold
            TaskStatus::ICO_OR_TRIBUNAL_OUTCOME                => TaskStatus::ICO_OR_TRIBUNAL_OUTCOME
        ];
        asort($taskArray);

        return $taskArray;
    }
}
