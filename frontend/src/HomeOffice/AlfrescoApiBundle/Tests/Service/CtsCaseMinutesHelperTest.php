<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\CtsCaseMinutesHelper;

class CtsCaseMinutesHelperTest extends \PHPUnit_Framework_TestCase
{

    protected $expected = array (
        'cqt approval',
        'private office approval'
    );

    public function testToArray()
    {
        foreach ($this->expected as $key) {
            $this->assertArrayHasKey($key, CtsCaseMinutesHelper::getCaseMinutesStatuses());
        }
    }
}
