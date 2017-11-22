<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentPostCode
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentPostCode
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentPostCode(FormBuilderInterface $builder)
    {
        $builder->add('correspondentPostcode', 'text', [
            'required' => false,
            'label'    => 'Postcode',
        ]);

        return $this;
    }
}
