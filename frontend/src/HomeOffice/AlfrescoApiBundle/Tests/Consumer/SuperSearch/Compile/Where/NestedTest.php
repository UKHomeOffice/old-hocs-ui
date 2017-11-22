<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\SuperSearch\Compile\Where;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where\Nested;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

class NestedTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSql()
    {
        $statement = (new Statement)->from('rasputin');

        $statement->where(function(Statement $query) {
            return $query->where('odin', 'cleopatra')->where('saturn', 'dangermouse');
        });

        $statement->where(function(Statement $query) {
            return $query->like('bad', 'spirit')->orWhere('penfold', 'banana');
        });

        $this->assertEquals(
            '((odin = \'cleopatra\' AND saturn = \'dangermouse\') AND (bad LIKE \'%spirit%\' OR penfold = \'banana\'))',
            (new Nested)->getSql($statement)
        );
    }
}
