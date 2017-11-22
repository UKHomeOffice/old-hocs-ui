<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;

/**
 * Class CtsCaseDocumentFactory
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class CtsCaseDocumentFactory extends CtsNodeFactory
{

    /**
     * @param array $parameters
     * @return CtsCaseDocument
     */
    public function build($parameters)
    {
        $ctsCaseDocument = new CtsCaseDocument($this->getWorkspace(), $this->getStore());

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($ctsCaseDocument, $methodName)) {
                $ctsCaseDocument->$methodName($value);
            }
        }

        return $ctsCaseDocument;
    }
}
