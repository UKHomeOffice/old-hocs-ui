<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondingName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondingName
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    public function correspondingName(FormBuilderInterface $builder)
    {
        $builder->add('correspondingName', 'text', [
            'required' => false,
            'label'    => 'Name of the party',
            'attr'     => ['placeholder' => 'Name of the party']
        ]);

        return $this;
    }
}
