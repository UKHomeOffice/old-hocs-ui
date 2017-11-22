<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CtsCaseMinuteQAOutcomesHelper
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CtsCaseMinuteQAOutcomesHelper extends ConstantHelper
{
    const QA_OPTION_SPELLING    = "Spelling";
    const QA_OPTION_GRAMMAR     = "Grammar";
    const QA_OPTION_TONE        = "Customer Service Tone";
    const QA_OPTION_STYLE       = "Style";
    const QA_OPTION_STRUCTURE   = "Structure";
    const QA_OPTION_FACTS       = "Facts";
    const QA_OPTION_MISSALLOC   = "Misallocation";
    const QA_OPTION_MISC        = "Non error/ Miscellaneous";
    const QA_OPTION_NO_ERRORS   = "No errors";
    const QA_OPTION_CODE_1      = "Code #1";
    const QA_OPTION_CODE_2      = "Code #2";
    const QA_OPTION_CODE_3      = "Code #3";
    const QA_OPTION_CODE_4      = "Code #4";
    const QA_OPTION_CODE_5      = "Code #5";

    /**
     * @return array
     */
    public static function getCaseMinuteQAOutcomes()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
