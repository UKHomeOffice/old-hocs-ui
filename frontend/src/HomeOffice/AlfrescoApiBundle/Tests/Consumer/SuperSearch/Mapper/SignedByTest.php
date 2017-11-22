<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\SignedBy;

class SignedByTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();
        $signedBy = new SignedBy();
        $signedBy->map($mapper, 'c.cts:signedByHomeSec', 'Home secretary');

        $where = $mapper->getWheres();
        $this->assertEquals(
            [
                'type'     => "Basic",
                'column'   => "c.cts:signedByHomeSec",
                'operator' => "=",
                'value'    => true,
                'boolean'  => "AND"
            ],
            reset($where)
        );
    }
}
