<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;

/**
 * Class CtsCaseStandardLineFactory
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class CtsCaseStandardLineFactory extends CtsNodeFactory
{
    /**
     * Build
     *
     * @param array $parameters
     *
     * @return CtsCaseStandardLine
     */
    public function build($parameters)
    {
        $standardLine = new CtsCaseStandardLine($this->getWorkspace(), $this->getStore());

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($standardLine, $methodName)) {
                $standardLine->$methodName($value);
            }
        }

        $standardLine->setOriginalName($standardLine->getName());

        return $standardLine;
    }
}
