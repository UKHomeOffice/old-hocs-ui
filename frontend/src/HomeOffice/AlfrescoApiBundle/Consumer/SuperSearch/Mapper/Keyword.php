<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

/**
 * Class Keyword
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class Keyword implements MapperInterface
{
    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        $mapper->where(function(Statement $query) use ($field, $value) {
            $query->like('c.cts:questionText', $value)->orLike('c.cts:answerText', $value);
        });
    }
}
