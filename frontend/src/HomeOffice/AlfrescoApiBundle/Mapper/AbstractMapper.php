<?php

namespace HomeOffice\AlfrescoApiBundle\Mapper;

/**
 * Class AbstractMapper                        
 *
 * @package HomeOffice\AlfrescoApiBundle\Mapper
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-08-05
 */
abstract class AbstractMapper
{
    /**
     * @var $replacements
     */
    protected $replacements;

    /**
     * Map
     *
     * @param array $response
     * @param bool $listHandler
     * @return mixed
     */
    abstract public function map(array $response, $listHandler = false);

    /**
     * Match
     *
     * @param $value
     * @return bool
     */
    protected function match($value)
    {
        return isset($this->replacements[$value]) ? $this->replacements[$value] : false;
    }
}

