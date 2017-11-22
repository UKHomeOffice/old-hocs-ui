<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\ReviewedBy;

class ReviewedByTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper();

        $reviewBy = new ReviewedBy();
        $reviewBy->map($mapper, 'reviewedBy', 'SpAds');

        $where = $mapper->getWheres();

        $this->assertEquals(
            [
                'type'     => "Basic",
                'column'   => "c.cts:reviewedBySpads",
                'operator' => "=",
                'value'    => true,
                'boolean'  => "AND"
            ],
            reset($where)
        );
    }

    public function testMapException()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'Reviewed by type [ThisIsNotAType] does not exist'
        );

        $mapper = new Mapper();

        $reviewBy = new ReviewedBy();
        $reviewBy->map($mapper, 'reviewedBy', 'ThisIsNotAType');
    }
}
