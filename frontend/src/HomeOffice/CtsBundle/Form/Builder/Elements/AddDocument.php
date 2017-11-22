<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddDocument
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AddDocument
{
    /**
     * @param  FormBuilderInterface $builder
     *
     * @return static
     */
    public function addDocument(FormBuilderInterface $builder)
    {
        $builder->add('addDocument', 'submit', [
            'attr' => ['class' => 'button-secondary btn'],
        ]);

        return $this;
    }
}
