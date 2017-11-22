<?php

namespace HomeOffice\CtsBundle\Form\DataTransformer;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class CaseToDocumentTransformer
 *
 * @package HomeOffice\CtsBundle\Form\DataTransformer
 */
class CaseToDocumentTransformer implements DataTransformerInterface
{
    /**
     * @param CtsCase $case
     *
     * @return CtsCaseDocument
     *
     */
    public function transform($case)
    {
        return new CtsCaseDocument($case->getWorkspace(), $case->getStore());
    }

    /**
     * @param mixed $value
     */
    public function reverseTransform($value)
    {
        return $value;
    }
}
