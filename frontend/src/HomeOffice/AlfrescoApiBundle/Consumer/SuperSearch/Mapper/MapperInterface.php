<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

/**
 * Interface MapperInterface
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
interface MapperInterface
{
    /**
     * @param  Mapper $mapper
     * @param  string $field
     * @param  string $value
     * @return void
     */
    public function map(Mapper $mapper, $field, $value);
}
