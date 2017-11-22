<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CtsHelpDocument;

/**
 * Class CtsCaseDocumentFactory
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class CtsHelpDocumentFactory extends CtsNodeFactory
{

    /**
     * @param array $parameters
     * @return CtsHelpDocument
     */
    public function build($parameters)
    {
        $ctsHelpDocument = new CtsHelpDocument($this->getWorkspace(), $this->getStore());

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($ctsHelpDocument, $methodName)) {
                $ctsHelpDocument->$methodName($value);
            }
        }

        return $ctsHelpDocument;
    }
}
