<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseTopics
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseTopics
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildTopicsForm(FormBuilderInterface $builder)
    {
        $builder->add('topics', 'CtsCaseTopics', [
            'label'          => '',
            'mapped'         => false,
            'data'           => $builder->getData(),
            'error_bubbling' => false,
        ]);
    }
}
