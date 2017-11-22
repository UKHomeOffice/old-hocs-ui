<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

/**
 * Class CtsCaseWorkflowTransition
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsCaseWorkflowTransition
{
    /**
     * @var string
     */
    private $label;
 
    /**
     * @var string
     */
    private $value;
 
    /**
     * @var bool
     */
    private $manualAllocate;
 
    /**
     * @var string
     */
    private $allocateHeader;
 
    /**
     * @var string
     */
    private $colour;
 
    /**
     *
     * @param string $label
     * @param string $value
     * @param bool   $manualAllocate
     * @param string $allocateHeader
     * @param string $colour
     */
    public function __construct($label, $value, $manualAllocate, $allocateHeader, $colour)
    {
        $this->label = $label;
        $this->value = $value;
        $this->manualAllocate = $manualAllocate;
        $this->allocateHeader = $allocateHeader;
        $this->colour = $colour;
    }

    /**
     * Get Label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set Label
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get Value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Value
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get ManualAllocate
     *
     * @return boolean
     * @deprecated in favour of isManualAllocate
     */
    public function getManualAllocate()
    {
        return $this->manualAllocate;
    }

    /**
     * Get ManualAllocate
     *
     * @return boolean
     */
    public function isManualAllocate()
    {
        return $this->manualAllocate;
    }

    /**
     * Set ManualAllocate
     *
     * @param boolean $manualAllocate
     *
     * @return $this
     */
    public function setManualAllocate($manualAllocate)
    {
        $this->manualAllocate = $manualAllocate;

        return $this;
    }

    /**
     * Get AllocateHeader
     *
     * @return string
     */
    public function getAllocateHeader()
    {
        return $this->allocateHeader;
    }

    /**
     * Set AllocateHeader
     *
     * @param string $allocateHeader
     *
     * @return $this
     */
    public function setAllocateHeader($allocateHeader)
    {
        $this->allocateHeader = $allocateHeader;

        return $this;
    }

    /**
     * Get Colour
     *
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * Set Colour
     *
     * @param string $colour
     *
     * @return $this
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

}
