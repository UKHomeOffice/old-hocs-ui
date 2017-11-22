<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentSurname
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentSurname
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $hidden
     *
     * @return static
     */
    protected function correspondentSurname(FormBuilderInterface $builder, $hidden = false)
    {
        $builder->add('correspondentSurname', $hidden ? 'hidden' : 'text', [
            'required' => false,
            'label'    => 'Last name'
        ]);

        return $this;
    }
}
