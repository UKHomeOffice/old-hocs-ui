<?php

namespace HomeOffice\ListBundle\DependencyInjection;

use HomeOffice\ListBundle\Service\ListService;

/**
 * Class ListHandlerAwareInterface
 *
 * @package HomeOffice\ListBundle\DependencyInjection
 */
interface ListServiceAwareInterface
{
    /**
     * Sets the Container.
     *
     * @param ListService|null $listService
     */
    public function setListService(ListService $listService = null);
}
