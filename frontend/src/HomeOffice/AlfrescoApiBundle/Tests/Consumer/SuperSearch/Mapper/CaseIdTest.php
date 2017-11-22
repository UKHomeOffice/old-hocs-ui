<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\CaseId;

class CaseIdTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();

        $caseId = new CaseId();
        $caseId->map($mapper, 'c.cts:caseId', 'MIN/0000001/17');

        $where = $mapper->getWheres();
        $this->assertEquals(
            [
                'type'     => "Basic",
                'column'   => "c.cts:caseId",
                'operator' => "LIKE",
                'value'    => "%0000001/17%",
                'boolean'  => "AND"
            ],
            reset($where)
        );
    }
}
