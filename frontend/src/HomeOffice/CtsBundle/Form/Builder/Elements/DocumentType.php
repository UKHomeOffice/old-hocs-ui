<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\CaseDocumentTypeHelper;

/**
 * Class DocumentType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DocumentType
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function documentType(FormBuilderInterface $builder)
    {
        $builder->add('documentType', 'choice', [
            'choices'     => CaseDocumentTypeHelper::getAvailableTypesForCase($builder->getData()),
            'empty_value' => '',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'chosen form-control',
                'data-placeholder' => 'Select document type',
            ],
        ]);

        return $this;
    }
}
