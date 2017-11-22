<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\ListBundle\Service\ListService;

/**
 * Class UnitExtension
 * @package HomeOffice\CtsBundle\Twig
 */
class UnitExtension extends \Twig_Extension
{
    /**
     * @var ListService
     */
    protected $listService;

    /**
     * Constructor
     *
     * @param ListService $listService
     */
    public function __construct(ListService $listService)
    {
        $this->listService = $listService;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'unit_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getUnitName', [$this, 'getNameById'])
        ];
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function getNameById($id)
    {
        foreach ($this->listService->getUnitArray() as $key => $value) {
            if ($key === $id) {
                return $value;
            }
        }

        return $id;
    }

}
