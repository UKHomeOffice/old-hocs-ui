<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class HmpoComplaint
{
    // Refund types
    const REFUND = 'Refund';
    const CONSOLATORY_PAYOUT = 'Consolatory payout';
    const REIMBURSEMENT = 'Reimbursement';
 
    // Outcome
    const UPHELD = 'Upheld';
    const DISMISSED = 'Dismissed';
 
    /**
     * Returns an array of all the constants required for refund decision.
     *
     * @return array
     */
    public static function getRefundDecisionArray()
    {
        return [
            self::REFUND             => self::REFUND,
            self::CONSOLATORY_PAYOUT => self::CONSOLATORY_PAYOUT,
            self::REIMBURSEMENT      => self::REIMBURSEMENT
        ];
    }
 
    /**
     * Returns an array of all the constants required for complaint outcome.
     *
     * @return array
     */
    public static function getComplaintOutcomeArray()
    {
        return [
            self::UPHELD    => self::UPHELD,
            self::DISMISSED => self::DISMISSED,
        ];
    }
}
