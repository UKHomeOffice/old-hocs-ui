<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\Keyword;

class KeywordTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $mapper = new Mapper;
        $keyword = new Keyword();
        $keyword->map($mapper, 'keyword', 'test');

        $where = $mapper->getWheres();
        $wheres = reset($where);

        $this->assertEquals('Nested', $wheres['type']);

        $subWheres = $wheres['query']->getWheres();
        $this->assertEquals('c.cts:questionText', $subWheres[0]['column']);
        $this->assertEquals('c.cts:answerText', $subWheres[1]['column']);
    }
}
