<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EditAnswer
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait EditAnswer
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function editAnswer(FormBuilderInterface $builder)
    {
        $builder->add('editAnswer', 'submit', [
            'attr' => ['class' => 'hidden editAnswer'],
        ]);

        return $this;
    }
}
