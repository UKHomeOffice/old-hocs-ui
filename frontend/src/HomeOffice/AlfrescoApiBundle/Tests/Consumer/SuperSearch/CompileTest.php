<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

class CompileTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $this->assertEquals('SELECT * FROM sue', Compile::build((new Statement)->from('sue')));
    }

    public function testFactoryException()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'Component [thisWillNeverBeAComponent] not found.'
        );
        $method = new \Reflectionmethod(Compile::class, 'factory');
        $method->setAccessible(true);
        $method->invoke(new Compile, 'thisWillNeverBeAComponent');



    }
}
