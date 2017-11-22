<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class Channel extends ConstantHelper
{
    const EMAIL = 'Email';
    const POST = 'Post';
    const LETTER = 'Letter';
    const PHONE = 'Phone';
    const FAX = 'Fax';
    const NO10 = 'No. 10';
    const FURTHER_ACTION = 'Further Action';
    const OUTREACH = 'Outreach';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getChannelArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
