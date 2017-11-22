<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class QuestionNumber
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait QuestionNumber
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function questionNumber(FormBuilderInterface $builder)
    {
        $builder->add('questionNumber', 'text', [
            'label' => 'Question No.',
        ]);

        return $this;
    }
}
