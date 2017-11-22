<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\ListBundle\Service\CSVParser;

class CSVParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var CSVParser
     */
    private $instance;
 
    public function setUp()
    {
        $this->instance = new CSVParser();
    }

    public function testToArray()
    {
        $csv = "\rItem 1,GROUP 1\rItem 2,GROUP 2";
        $expected = array(
            array('Item 1', 'GROUP 1'),
            array('Item 2', 'GROUP 2'),
        );
        $array = $this->instance->toArray($csv);
        $this->assertEquals($expected, $array);
    }

    public function testToCollapsedArrayDefault()
    {
        $csv = "\rItem 1,GROUP 1\rItem 2,GROUP 2";
        $expected = array(
            'Item 1' => 'Item 1',
            'Item 2' => 'Item 2'
        );
        $array = $this->instance->toCollapsedArray($csv);
        $this->assertEquals($expected, $array);
    }

    public function testToCollapsedArrayWithIndexes()
    {
        $csv = "\rItem 1,GROUP 1\rItem 2,GROUP 2";
        $expected = array(
            'GROUP 1' => 'Item 1',
            'GROUP 2' => 'Item 2'
        );
        $array = $this->instance->toCollapsedArray($csv, 1, 0);
        $this->assertEquals($expected, $array);
    }

    public function testCollapseCsvArray()
    {
        $csvArray = array(
            array('Item 1'),
            array('Item 2'),
        );
        $expected = array(
            'Item 1' => 'Item 1',
            'Item 2' => 'Item 2'
        );
        $array = $this->instance->collapseCsvArray($csvArray);
        $this->assertEquals($expected, $array);
    }
}
