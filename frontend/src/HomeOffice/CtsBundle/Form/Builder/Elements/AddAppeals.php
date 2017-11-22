<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddAppeals
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AddAppeals
{
    /**
     * @param  FormBuilderInterface $builder
     *
     * @return static
     */
    public function addAppeals(FormBuilderInterface $builder)
    {
        $builder->add('addAppeals', 'submit', [
            'label' => 'Link',
            'attr'  => ['class' => 'button btn'],
        ]);

        return $this;
    }
}
