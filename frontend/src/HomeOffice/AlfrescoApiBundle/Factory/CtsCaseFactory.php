<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

/**
 * Class CtsCaseFactory
 *
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class CtsCaseFactory extends CtsNodeFactory
{
    /**
     * @var CTSHelper
     */
    private $ctsHelper;

    /**
     * Constructor
     *
     * @param string    $workspace
     * @param string    $store
     * @param CTSHelper $ctsHelper
     */
    public function __construct($workspace, $store, CTSHelper $ctsHelper)
    {
        parent::__construct($workspace, $store);
        $this->ctsHelper = $ctsHelper;
    }

    /**
     * @param array $parameters
     * @param string $type
     *
     * @return CtsCase
     */
    public function build($parameters, $type = CtsCase::class)
    {
        return $this->buildPermissions(
            $parameters,
            $this->buildCtsCase($parameters, new $type($this->getWorkspace(), $this->getStore()), $type)
        );
    }

    /**
     * @param mixed   $parameters
     * @param CtsCase $ctsCase
     * @param string  $type
     *
     * @return CtsCase
     */
    protected function buildCtsCase($parameters, $ctsCase, $type)
    {
        $case = isset($parameters->ctsCase) ? $parameters->ctsCase : $parameters;
        foreach ($case as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($ctsCase, $methodName)) {
                if ($methodName == 'setGroupedCases' || $methodName == 'setLinkedCases') {
                    $value = $this->buildGroupedLinkedCases($value, $type);
                }

                if ($methodName == 'setMarkupMinister') {
                    $ctsCase->setMarkupMinisterName($this->ctsHelper->getListHandler()->getMinisterName($value));
                }

                $ctsCase->$methodName($value);
            }
        }

        return $ctsCase;
    }

    /**
     * @param $parameters
     * @param CtsCase $ctsCase
     *
     * @return CtsCase
     */
    private function buildPermissions($parameters, CtsCase $ctsCase)
    {
        if (isset($parameters->properties->permissions)) {
            foreach ($parameters->properties->permissions as $key => $value) {
                $methodName = "set" . ucfirst($key);
                if (method_exists($ctsCase, $methodName)) {
                    $ctsCase->$methodName($value);
                }
            }
        }

        return $ctsCase;
    }

    /**
     * Build CtsCase objects from an array of cases with properties.
     * @param array $cases
     * @param string $type
     * @return array
     */
    private function buildGroupedLinkedCases(array $cases, $type)
    {
        $newCases = array();
        foreach ($cases as $case) {
            $type = $this->ctsHelper->getCaseClassFromType($case->correspondenceType);
            array_push($newCases, $this->build((array) $case, $type));
        }
        return $newCases;
    }
}
