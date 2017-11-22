<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class DocumentTemplateFileTags
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class DocumentTemplateFileTags extends ConstantHelper
{
    /**
     * Can be serialized with a / delimiter
     */
    const COL = 'passportNumber';

    /**
     * @param $correspondenceType
     *
     * @return string[]
     */
    public static function getForCorrespondenceType($correspondenceType)
    {
        if (defined('self::'.$correspondenceType)) {
            $fileTags = explode('/', constant('self::' . $correspondenceType));
        } else {
            $fileTags = [];
        }

        return $fileTags;
    }

    /**
     * @return string
     */
    protected static function getStaticClass()
    {
        return self::class;
    }
}
