<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

class StatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Statement
     */
    private $statement;

    public function setUp()
    {
        parent::setUp();

        $this->statement = new Statement();
    }

    public function testWhereAndGetWheres()
    {
        $response = $this->statement->where('bob', 'mary', '<>', 'OR');

        $this->assertEquals(
            [
                'type'     => 'Basic',
                'column'   => 'bob',
                'operator' => '<>',
                'value'    => 'mary',
                'boolean'  => 'OR'
            ],
            $this->statement->getWheres()[0]
        );

        $this->statement->where('harry');

        $this->assertEquals(
            [
                'type'     => 'Basic',
                'column'   => 'harry',
                'operator' => '=',
                'value'    => null,
                'boolean'  => 'AND'
            ],
            $this->statement->getWheres()[1]
        );

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testFromAndGetFrom()
    {
        $response = $this->statement->from('barry');
        $this->assertEquals('barry', $this->statement->getFrom());

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testSelectAndGetColumns()
    {
        $response = $this->statement->select('susan');
        $this->assertEquals(['susan'], $this->statement->getColumns());

        $this->statement->select(['john', 'kevin', 'katie']);
        $this->assertEquals(['john', 'kevin', 'katie'], $this->statement->getColumns());

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testAddSelect()
    {
        $this->statement->select('bob');
        $response = $this->statement->addSelect('mary');
        $this->assertEquals(['bob', 'mary'], $this->statement->getColumns());

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testLike()
    {
        $response = $this->statement->like('harry', 'susan');
        $this->assertEquals(
            [
                'type'     => 'Basic',
                'column'   => 'harry',
                'operator' => 'LIKE',
                'value'    => '%susan%',
                'boolean'  => 'AND'
            ],
            $this->statement->getWheres()[0]
        );

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testOrWhere()
    {
        $response = $this->statement->orWhere('mary', 'hannah');
        $this->assertEquals(
            [
                'type'     => 'Basic',
                'column'   => 'mary',
                'operator' => '=',
                'value'    => 'hannah',
                'boolean'  => 'OR'

            ],
            $this->statement->getWheres()[0]
        );

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testWhereNested()
    {
        $response = $this->statement->where(
            function(Statement $query) {
                $query->where('phillip', 'mike')->where('nicky', 'helen');
            }
        );

        $where = $this->statement->getWheres();
        $where = reset($where);

        $this->assertEquals('Nested', $where['type']);
        $this->assertInstanceOf(Statement::class, $where['query']);

        $this->assertInstanceOf(Statement::class, $response);
    }

    public function testGenerateException()
    {
        $this->setExpectedException(\RuntimeException::class, 'From must be set to execute a query.');

        $this->statement->generate();
    }

    public function testGenerate()
    {
        $this->statement
            ->select(['the', 'quick', 'brown', 'fox'])
            ->from('jumps')
            ->where('over', 'the')
            ->where('lazy', 'dog');

        $this->assertEquals(
            "SELECT the, quick, brown, fox FROM jumps WHERE over = 'the' AND lazy = 'dog'",
            $this->statement->generate()
        );
    }
}
