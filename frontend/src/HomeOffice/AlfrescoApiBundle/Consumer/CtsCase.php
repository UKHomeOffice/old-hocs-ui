<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

/**
 * Class CtsCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-05-13
 */
class CtsCase extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/homeoffice/ctsv2/case';

    /**
     * @param string|bool $nodeRef
     * @return bool|string
     */
    public function get($nodeRef = false)
    {
        $this->setQueryField('nodeRef', $nodeRef);
        return parent::get();
    }
}
