<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class IndividualHousehold
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait IndividualHousehold
{
    /**
     * @param FormBuilderInterface $builder
     * @return static
     */
    protected function individualHousehold(FormBuilderInterface $builder)
    {
        $builder->add('individualHousehold', 'choice', [
            'choices'    => [true  => 'Yes', false => 'No'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline individualHouseholdTrigger'],
            'label'      => 'Will the party be staying in individual households?',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
