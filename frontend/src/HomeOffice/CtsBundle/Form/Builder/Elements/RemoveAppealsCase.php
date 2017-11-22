<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RemoveAppealsCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RemoveAppealsCase
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function removeAppealsCase(FormBuilderInterface $builder)
    {
        $builder->add('removeAppealsCase', 'submit', [
            'attr' => ['class' => 'hidden removeLinkedCase'],
        ]);

        return $this;
    }
}
