<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

class From implements CompileInterface
{
    /**
     * @param  Statement $query
     * @return string
     */
    public function getSql(Statement $query)
    {
        return 'FROM ' . $query->getFrom();
    }
}
