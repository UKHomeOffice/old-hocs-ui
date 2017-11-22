<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

/**
 * Interface CompileInterface
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement\Compile
 */
interface CompileInterface
{
    /**
     * @param  Statement $query
     * @return string
     */
    public function getSql(Statement $query);

}
