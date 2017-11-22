<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Service\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Paginator
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new Paginator();
    }

    public function testGetSetPageSize()
    {
        $this->instance->setPageSize(1);
        $this->assertEquals(1, $this->instance->getPageSize());
    }

    public function testGetSetPageNumber()
    {
        $this->instance->setPageNumber(2);
        $this->assertEquals(2, $this->instance->getPageNumber());
    }

    public function testGetSetTotalResults()
    {
        $this->instance->setTotalResults(100);
        $this->assertEquals(100, $this->instance->getTotalResults());
    }

    public function testGetSetPagePath()
    {
        $this->instance->setPagePath('page_path');
        $this->assertEquals('page_path', $this->instance->getPagePath());
    }
 
    public function testGetPageSizeDefault()
    {
        $this->assertEquals(20, $this->instance->getPageSize());
    }
 
    public function testSetPageNumber()
    {
        $this->instance->setPageNumber(-1);
        $this->assertEquals(1, $this->instance->getPageNumber());
        $this->instance->setPageNumber(0);
        $this->assertEquals(1, $this->instance->getPageNumber());
    }
 
    public function testCalculateSkipCount()
    {
        $this->instance->setPageNumber(2);
        $this->assertEquals(20, $this->instance->calculateSkipCount());
        $this->instance->setPageSize(50);
        $this->instance->setPageNumber(3);
        $this->assertEquals(100, $this->instance->calculateSkipCount());
    }
 
    public function testCalculateNumberOfPages()
    {
        $this->instance->setTotalResults(100);
        $this->assertEquals(5, $this->instance->calculateNumberOfPages());
        $this->instance->setTotalResults(61);
        $this->assertEquals(4, $this->instance->calculateNumberOfPages());
    }
 
    public function testGetPages()
    {
        $this->instance->setTotalResults(100);
        $expectedPages = array(1,2,3);
        $this->assertEquals($expectedPages, $this->instance->getPages());
     
        $this->instance->setPageNumber(4);
        $this->instance->setTotalResults(200);
        $expectedPages = array(1,2,3,4,5,6,7);
        $this->assertEquals($expectedPages, $this->instance->getPages());
     
        $this->instance->setPageNumber(8);
        $this->instance->setTotalResults(200);
        $expectedPages = array(5,6,7,8,9,10);
        $this->assertEquals($expectedPages, $this->instance->getPages());
     
        $this->instance->setPageNumber(10);
        $this->instance->setTotalResults(200);
        $expectedPages = array(7,8,9,10);
        $this->assertEquals($expectedPages, $this->instance->getPages());
    }
 
    public function testShowPreviousLink()
    {
        $this->instance->setPageNumber(1);
        $this->assertEquals(false, $this->instance->showPreviousLink());
        $this->instance->setPageNumber(2);
        $this->assertEquals(true, $this->instance->showPreviousLink());
    }
 
    public function testShowNextLink()
    {
        $this->instance->setPageNumber(1);
        $this->instance->setTotalResults(60);
        $this->assertEquals(true, $this->instance->showNextLink());
        $this->instance->setPageNumber(3);
        $this->assertEquals(false, $this->instance->showNextLink());
    }
}
