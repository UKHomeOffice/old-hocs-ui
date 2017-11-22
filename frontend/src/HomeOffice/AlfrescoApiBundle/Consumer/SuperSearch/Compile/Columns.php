<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

/**
 * Class Columns
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement\Compile
 */
class Columns implements CompileInterface
{
    /**
     * @param  Statement $query
     * @return string
     */
    public function getSql(Statement $query)
    {
        return 'SELECT ' . implode(', ', $query->getColumns());
    }
}
