<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

/**
 * Class ReviewedBy
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class ReviewedBy implements MapperInterface
{
    /**
     * @var array
     */
    private $reviewedBy = [
        'SpAds'    => 'c.cts:reviewedBySpads',
        'Perm sec' => 'c.cts:reviewedByPermSec'
    ];

    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        if (array_key_exists($value, $this->reviewedBy)) {
            $mapper->where($this->reviewedBy[$value], true);
            return;
        }

        throw new \InvalidArgumentException('Reviewed by type [' . $value . '] does not exist');
    }
}
