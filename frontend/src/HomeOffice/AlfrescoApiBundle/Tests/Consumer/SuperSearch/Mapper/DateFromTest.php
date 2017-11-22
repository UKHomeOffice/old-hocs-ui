<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\DateFrom;

class DateFromTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();

        $dateFrom = new DateFrom();
        $dateFrom->map($mapper, 'c.cts:dateReceived', '2017-01-17T16:01:39+00:00');

        $where = $mapper->getWheres();
        $this->assertEquals(
            [
                'type'     => "Basic",
                'column'   => "c.cts:dateReceived",
                'operator' => ">=",
                'value'    => "2017-01-17T16:01:39+00:00",
                'boolean'  => "AND"
            ],
            reset($where)
        );
    }
}
