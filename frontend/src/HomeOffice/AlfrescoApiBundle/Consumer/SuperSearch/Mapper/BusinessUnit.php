<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;

/**
 * Class BusinessUnit
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class BusinessUnit implements MapperInterface
{
    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        $mapper->where(function(Statement $query) use ($field, $value) {
            foreach (CaseCorrespondenceType::getSubtypesFromCaseType($value) as $unit) {
                $query->orWhere($field, $unit);
            }
        });
    }
}
