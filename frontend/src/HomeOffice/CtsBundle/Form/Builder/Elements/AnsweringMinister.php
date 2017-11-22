<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AnsweringMinister
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AnsweringMinister
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $memberList
     * @param string               $label
     * @param string               $placeholder
     * @param bool                 $flexible
     *
     * @return static
     */
    public function answeringMinister(
        FormBuilderInterface $builder,
        array $memberList = [],
        $label = 'Answering unit',
        $placeholder = 'Select unit',
        $flexible = false
    ) {
         $builder->add('answeringMinister', 'choice', [
             'choices'     => $memberList,
             'empty_value' => '',
             'required'    => false,
             'label'       => $label,
             'label_attr'  => ['class' => 'form-label'],
             'attr'        => [
                 'class'            => 'form-control ' . ($flexible ? 'flexible-chosen' : 'chosen'),
                 'data-placeholder' => $placeholder,

             ],
         ]);

        return $this;
    }
}
