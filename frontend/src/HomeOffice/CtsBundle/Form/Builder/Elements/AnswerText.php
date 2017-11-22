<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AnswerText
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AnswerText
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function answerText(FormBuilderInterface $builder)
    {
        $builder->add('answerText', 'textarea', [
            'label' => 'Response',
            'attr'  => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
            ],
        ]);

        return $this;
    }
}
