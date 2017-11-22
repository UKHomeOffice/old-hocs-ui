<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\BulkDocument;

/**
 * Class CtsCaseDocumentFactory
 * @package HomeOffice\AlfrescoApiBundle\Factory
 */
class BulkDocumentFactory extends CtsNodeFactory
{

    /**
     * @param array $parameters
     * @return BulkDocument
     */
    public function build($parameters)
    {
        $bulkDocument = new BulkDocument($this->getWorkspace(), $this->getStore());

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($bulkDocument, $methodName)) {
                $bulkDocument->$methodName($value);
            }
        }

        return $bulkDocument;
    }
}
