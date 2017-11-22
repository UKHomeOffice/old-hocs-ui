<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\CompileInterface;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where;

/**
 * Class Nested
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where
 */
class Nested implements CompileInterface
{
    /**
     * @param  Statement $query
     * @return string
     */
    public function getSql(Statement $query)
    {
        return '(' . substr(Where::generate($query->getWheres()), 6) . ')';
    }
}
