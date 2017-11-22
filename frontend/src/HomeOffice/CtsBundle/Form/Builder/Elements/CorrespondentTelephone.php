<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentTelephone
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentTelephone
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentTelephone(FormBuilderInterface $builder)
    {
        $builder->add('correspondentTelephone', 'text', [
            'required' => false,
            'label'    => 'Telephone',
        ]);

        return $this;
    }
}
