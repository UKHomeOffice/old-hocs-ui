<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentForename
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentForename
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $hidden
     *
     * @return static
     */
    protected function correspondentForename(FormBuilderInterface $builder, $hidden = false)
    {
        $builder->add('correspondentForename', $hidden ? 'hidden' : 'text', [
            'label'    => 'First name',
            'required' => false,
        ]);

        return $this;
    }
}
