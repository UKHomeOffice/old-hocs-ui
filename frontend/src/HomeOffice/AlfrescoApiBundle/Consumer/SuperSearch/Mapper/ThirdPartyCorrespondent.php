<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement;

/**
 * Class ThirdPartyCorrespondent
 * 
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper
 */
class ThirdPartyCorrespondent implements MapperInterface
{
    /**
     * @var array
     */
    private $correspondentMap = [
        'c.cts:correspondentForename' => 'c.cts:thirdPartyCorrespondentForename',
        'c.cts:correspondentSurname'  => 'c.cts:thirdPartyCorrespondentSurname',
        'c.cts:correspondentPostcode' => 'c.cts:thirdPartyCorrespondentPostcode',
        'c.cts:correspondentEmail'    => 'c.cts:thirdPartyCorrespondentEmail'
    ];

    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $value
     */
    public function map(Mapper $mapper, $field, $value)
    {
        if ($mapper->getBusinessUnit() === 'UKVI' && array_key_exists($field, $this->correspondentMap)) {
            $this->query($mapper, $field, $this->correspondentMap[$field], $value);
        }
    }

    /**
     * @param Mapper $mapper
     * @param string $field
     * @param string $thirdPartyField
     * @param mixed  $value
     */
    private function query(Mapper $mapper, $field, $thirdPartyField, $value)
    {
        $mapper->where(function(Statement $query) use ($field, $thirdPartyField, $value) {
            $query->orWhere($field, $value)->orWhere($thirdPartyField, $value);
        });
    }
}
