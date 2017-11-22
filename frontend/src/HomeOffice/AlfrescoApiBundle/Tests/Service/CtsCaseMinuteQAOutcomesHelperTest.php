<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\CtsCaseMinuteQAOutcomesHelper;

class CtsCaseMinuteQAOutcomesHelperTest extends \PHPUnit_Framework_TestCase
{

    protected $expected = array (
        "Spelling",
        "Grammar",
        "Customer Service Tone",
        "Style",
        "Structure",
        "Facts",
        "Misallocation",
        "Non error/ Miscellaneous",
        "No errors",
        "Code #1",
        "Code #2",
        "Code #3",
        "Code #4",
        "Code #5",
    );

    public function testToArray()
    {
        foreach ($this->expected as $key) {
            $this->assertArrayHasKey($key, CtsCaseMinuteQAOutcomesHelper::getCaseMinuteQAOutcomes());
        }
    }
}
