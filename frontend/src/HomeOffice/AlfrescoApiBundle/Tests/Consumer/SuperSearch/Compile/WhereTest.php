<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Compile;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

class WhereTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSql()
    {
         $statement = (new Statement)->from('superman')->where('pullman', 'phillip')->select('silly', 'billy');
         $this->assertInstanceOf(Statement::class, $statement);

         $this->assertEquals('SELECT silly, billy FROM superman WHERE pullman = \'phillip\'', $statement->generate());
    }

    public function testFactoryException()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Class type [flumpyburn] missing.');
        Where::generate(
        [[
                'column'   => 'some',
                'value'    => 'mothers',
                'type'     => 'flumpyburn',
                'operator' => '=',
                'boolean'  => 'AND'
            ]]
        );
    }

    public function testLoopException()
    {
        $this->setExpectedException(\InvalidArgumentException::class, '$data[\'type\'] must be specified.');
        Where::generate(
            [[
                'column'   => 'some',
                'value'    => 'mothers',
                'operator' => '=',
                'boolean'  => 'AND'
            ]]
        );
    }

    public function testGenerate()
    {
        $this->assertEquals('', Where::generate([]));
    }
}
