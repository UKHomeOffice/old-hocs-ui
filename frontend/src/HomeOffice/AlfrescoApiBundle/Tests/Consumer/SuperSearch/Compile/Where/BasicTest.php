<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Compile\Where;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where\Basic;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSql()
    {
        $this->assertEquals(
            "bob <> '2017-01-17T16:01:39+00:00'",
            (new Basic)->getSql(
                [
                    'value'    => new \DateTime('2017-01-17T16:01:39+00:00'),
                    'column'   => 'bob',
                    'operator' => '<>',
                    'boolean'  => 'AND'
                ]
            )
        );
    }
}
