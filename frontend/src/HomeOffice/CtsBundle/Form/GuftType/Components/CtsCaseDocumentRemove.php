<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseDocumentRemove
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseDocumentRemove
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildDocumentRemoveForm(FormBuilderInterface $builder)
    {
        $builder->add('removeDocument', 'CtsCaseDocumentRemove', [
            'mapped' => false,
        ]);
    }
}
