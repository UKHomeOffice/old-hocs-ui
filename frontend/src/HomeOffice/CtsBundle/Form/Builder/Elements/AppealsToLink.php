<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AppealsToLink
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AppealsToLink
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function appealsToLink(FormBuilderInterface $builder)
    {
        $builder->add('appeals', 'text', [
            'label' => 'Enter case ID',
        ]);

        return $this;
    }
}
