<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

/**
 * Class DateTo
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class DateTo implements MapperInterface
{
    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        $mapper->where($field, $value, '<=');
    }

}
