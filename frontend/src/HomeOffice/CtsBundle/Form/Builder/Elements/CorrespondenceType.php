<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondenceType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondenceType
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondenceType(FormBuilderInterface $builder)
    {
        $case = $builder->getData();

        $builder->add('correspondenceType', 'text', [
            'data'     => $case
                ? CaseCorrespondenceSubType::getCorrespondenceSubTypeArray()[$case->getCorrespondenceType()] : null,
            'label'    => 'Case type',
            'disabled' => true,
        ]);

        return $this;
    }
}
