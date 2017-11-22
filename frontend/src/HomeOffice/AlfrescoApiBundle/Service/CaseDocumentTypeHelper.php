<?php
namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use ReflectionClass;

/**
 * Class CaseDocumentTypeHelper
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CaseDocumentTypeHelper
{
    const TYPE_ORIGINAL               = 'Original';
    const TYPE_ORIGINAL_ATTACHMENTS   = 'Original attachments';
    const TYPE_CONTRIBUTION           = 'Contribution';
    const TYPE_BACKGROUND_NOTE        = 'Background note';
    const TYPE_RESPONSE               = 'Response';
    const TYPE_OTHER                  = 'Other';


    const TYPE_ORIGINAL_APPLICATION   = 'Original application';
    const TYPE_ACKNOWLEDGEMENT_LETTER = 'Acknowledgement letter';
    const TYPE_QUERY_LETTER           = 'Query letter';
    const TYPE_COVER_LETTER           = 'Cover letter';
    const TYPE_COLLECTIVE_PASSPORT    = 'Collective passport';

    private static $default = [
        self::TYPE_ORIGINAL,
        self::TYPE_ORIGINAL_ATTACHMENTS,
        self::TYPE_CONTRIBUTION,
        self::TYPE_BACKGROUND_NOTE,
        self::TYPE_RESPONSE,
        self::TYPE_OTHER
    ];

    private static $hmpoCollectives = [
        self::TYPE_ORIGINAL_APPLICATION,
        self::TYPE_ACKNOWLEDGEMENT_LETTER,
        self::TYPE_QUERY_LETTER,
        self::TYPE_COVER_LETTER,
        self::TYPE_COLLECTIVE_PASSPORT,
        self::TYPE_OTHER
    ];

    /**
     * @param CtsCase $case
     *
     * @return array
     */
    public static function getAvailableTypesForCase(CtsCase $case)
    {
        $types = $case->getCorrespondenceType() === 'COL' ? self::$hmpoCollectives : self::$default ;
        return array_combine($types, $types);
    }

    /**
     * @return array
     */
    public function getAllTypes()
    {
        $types = (new ReflectionClass(__CLASS__))->getConstants();

        return array_combine($types, $types);
    }
}
