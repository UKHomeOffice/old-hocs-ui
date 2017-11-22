<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Dashboard;

use HomeOffice\AlfrescoApiBundle\Consumer\Dashboard as Consumer;

/**
 * Class Dashboard
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Dashboard
 */
class Dashboard
{
    /**
     * @var Consumer
     */
    protected $consumer;

    /**
     * Constructor
     *
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * @return DashboardChart
     *
     * @throws \Exception
     */
    public function getChart()
    {
        $data = $this->consumer->get();
        if ($data === false) {
            throw new \Exception('Could not retrieve data from Alfresco');
        }

        return new DashboardChart($data);
    }
}
