<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class TopicUnits extends ConstantHelper
{

    const topicUnitList = [
        'DCU' => 'DCU',
        'FOI' => 'FOI',
        'HMPO' => 'HMPO',
        'UKVI' => 'UKVI'
    ];

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getTopicUnitList()
    {
        return self::topicUnitList;
    }
}
