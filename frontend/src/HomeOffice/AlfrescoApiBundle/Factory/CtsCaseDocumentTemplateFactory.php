<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;

/**
 * Class CtsCaseDocumentFactory
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class CtsCaseDocumentTemplateFactory extends CtsNodeFactory
{

    /**
     * @param array $parameters
     * @return CtsCaseDocumentTemplate
     */
    public function build($parameters)
    {
        $ctsCaseDocumentTemplate = new CtsCaseDocumentTemplate($this->getWorkspace(), $this->getStore());

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($ctsCaseDocumentTemplate, $methodName)) {
                $ctsCaseDocumentTemplate->$methodName($value);
            }
        }

        return $ctsCaseDocumentTemplate;
    }
}
