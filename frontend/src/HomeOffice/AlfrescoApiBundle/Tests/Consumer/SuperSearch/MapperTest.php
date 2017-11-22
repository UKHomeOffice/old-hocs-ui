<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function testCaseTypeOverride()
    {
        $mapper = new Mapper();
        $where = $mapper->map(['businessUnit' => 'DCU', 'caseType' => 'MIN'])->getWheres();

        $this->assertEquals(
            [
                'type'     => 'Basic',
                'column'   => 'c.cts:correspondenceType',
                'value'    => 'MIN',
                'boolean'  => 'AND',
                'operator' => '='
            ],
            reset($where)
        );
    }

    public function testNullParameter()
    {
        $mapper = new Mapper();
        $this->assertEmpty($mapper->map(['caseType' => null])->getWheres());
    }

    public function testFactory()
    {
        $mapper = new Mapper();

        $where = $mapper->map(['caseId' => 'MIN/0000001/17'])->getWheres();

        $this->assertEquals(
            [
                'type'     => "Basic",
                'column'   => "c.cts:urnSuffix",
                'operator' => "LIKE",
                'value'    => "%0000001/17%",
                'boolean'  => "AND"
            ],
            reset($where)
        );
    }
}
