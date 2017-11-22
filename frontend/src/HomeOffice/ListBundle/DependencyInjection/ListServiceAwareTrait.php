<?php

namespace HomeOffice\ListBundle\DependencyInjection;

use HomeOffice\ListBundle\Service\ListService;

/**
 * Class ListServiceAwareTrait
 *
 * @package HomeOffice\ListBundle\DependencyInjection
 */
trait ListServiceAwareTrait
{
    /**
     * @var ListService
     */
    protected $listService;

    /**
     * @param ListService $listService
     */
    public function setListService(ListService $listService = null)
    {
        $this->listService = $listService;
    }
}
