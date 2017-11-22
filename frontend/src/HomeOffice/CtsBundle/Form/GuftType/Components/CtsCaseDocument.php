<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseDocument
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseDocument
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildDocumentForm(FormBuilderInterface $builder)
    {
        $builder->add('newDocument', 'CtsCaseDocument', [
            'data'   => $builder->getData()
        ]);
    }
}
