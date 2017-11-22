<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class QuestionText
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait QuestionText
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function questionText(FormBuilderInterface $builder)
    {
        $builder->add('questionText', 'textarea', [
            'attr'  => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
            ],
        ]);

        return $this;
    }
}
