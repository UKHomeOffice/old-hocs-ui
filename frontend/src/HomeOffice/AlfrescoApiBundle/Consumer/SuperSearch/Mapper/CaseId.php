<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

/**
 * Class CaseId
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class CaseId implements MapperInterface
{
    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        $value = preg_replace('/^([A-Z|a-z]*\/)/', '', $value);

        $mapper->where($field, '%'.$value.'%', 'LIKE');
    }

}
