<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class File
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait File
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function file(FormBuilderInterface $builder)
    {
        $builder->add('file', 'file', [
            'label_attr' => ['class' => 'hidden'],
            'attr'       => ['class' => 'document-upload-class hidden'],
        ]);

        return $this;
    }
}
