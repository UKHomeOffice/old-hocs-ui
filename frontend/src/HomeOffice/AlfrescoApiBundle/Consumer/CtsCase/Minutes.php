<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\CtsCase;

use HomeOffice\AlfrescoApiBundle\Consumer\AbstractConsumer;

/**
 * Class Minutes
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\CtsCase
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-05-13
 */
final class Minutes extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/cts/minutes';

    /**
     * @param bool $nodeRef
     * @return bool|string
     */
    public function get($nodeRef = false)
    {
        $this->setQueryField('nodeRef', 'workspace://SpacesStore/' . $nodeRef);
        return parent::get();
    }
}
