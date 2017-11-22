<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class HmpoResponse
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class HmpoResponse extends ConstantHelper
{
    const EMAIL  = 'Email';
    const POST   = 'Post';
    const PHONE  = 'Phone';
    const FAX    = 'Fax';
    const NO10   = 'No. 10';
    const LETTER = 'Letter';
    const OUTREACH = 'Outreach';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getHmpoResponseArray()
    {
        return self::getAll(true);
    }
}
