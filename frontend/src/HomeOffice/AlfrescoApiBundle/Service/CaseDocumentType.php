<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CaseDocumentType
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 *
 * @deprecated Use CaseDocumentTypeHelper
 */
abstract class CaseDocumentType extends ConstantHelper
{
    const ORIGINAL = 'Original';
    const ORIGINAL_ATTACHMENTS = 'Original attachments';
    const CONTRIBUTION = 'Contribution';
    const BACKGROUND_NOTE = 'Background note';
    const RESPONSE = 'Response';
    const OTHER = 'Other';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getCaseDocumentTypeArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
