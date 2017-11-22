<?php

namespace HomeOffice\AlfrescoApiBundle\Service\DocumentPopulator;

use HomeOffice\AlfrescoApiBundle\Service\DocumentPopulator\Exception\InvalidPartsException;

/**
 * Class DocumentVariable
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\DocumentPopulator
 */
class DocumentVariable
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $parameters = [];

    /**
     * Constructor
     *
     * @param string $key
     * @param string $delimiter
     */
    public function __construct($key, $delimiter = ':')
    {
        $this->key = $key;
        $this->populateParts($delimiter);
    }

    /**
     * Get Key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Parameters
     *
     * @return string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get Parameter
     *
     * @param int $number
     *
     * @return string|null
     */
    public function getParameter($number)
    {
        if (array_key_exists($number - 1, $this->parameters)) {
            return $this->parameters[$number - 1];
        }

        return null;
    }

    /**
     * @param string $delimiter
     */
    private function populateParts($delimiter)
    {
        $parts = explode($delimiter, $this->key);

        if (count($parts) < 2) {
            throw new InvalidPartsException(
                sprintf('The minimum number of variable parts is 2 (%s)', $this->key)
            );
        }

        $this->type = $parts[0];
        $this->name = $parts[1];

        if (count($parts) > 2) {
            for ($x=2; $x<=count($parts)-1; $x++) {
                array_push($this->parameters, $parts[$x]);
            }
        }
    }
}
