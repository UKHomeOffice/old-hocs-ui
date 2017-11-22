<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\BusinessUnit;

class BusinessUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();

        $businessUnit = new BusinessUnit();
        $businessUnit->map($mapper, 'c.cts:correspondenceType', 'DCU');

        $where = $mapper->getWheres();

        $this->assertEquals('Nested', reset($where)['type']);
    }
}
