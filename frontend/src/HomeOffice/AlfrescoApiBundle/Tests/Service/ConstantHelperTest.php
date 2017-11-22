<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\ConstantHelper;

/**
 * Class ConstantHelperTest
 *
 * @package HomeOffice\AlfrescoApiBundle\Tests\Service
 */
class ConstantHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Filter Constants
     */
    public function testFilterConstants()
    {
        $array = ['bob' => 'bob', 'mary' => 'mary'];

        $this->assertEquals($array, ConstantHelper::filterConstants($array));
        $this->assertNotEquals($array, ConstantHelper::filterConstants($array, ['mary' => 'mary']));
        $this->assertEquals(['bob' => 'bob'], ConstantHelper::filterConstants($array, ['mary' => 'mary']));
    }
}
