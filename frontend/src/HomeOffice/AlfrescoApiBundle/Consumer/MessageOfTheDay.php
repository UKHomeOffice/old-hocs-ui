<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

/**
 * Class MessageOfTheDay
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-05-10
 */
final class MessageOfTheDay extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/cmis/p/Guest%20Home/message-of-the-day/content';

    /**
     * @var bool
     */
    protected $guest = true;
}
