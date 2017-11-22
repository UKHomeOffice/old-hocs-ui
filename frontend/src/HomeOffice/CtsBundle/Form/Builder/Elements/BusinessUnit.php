<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BusinessUnit
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait BusinessUnit
{
    /**
     * @param FormBuilderInterface $builder
     * @param string|null          $selected
     *
     * @return static
     */
    public function businessUnit(FormBuilderInterface $builder, $selected = null)
    {
        $choices = array_merge(['' => 'All'], CaseCorrespondenceType::getCorrespondenceArray());

        $builder->add('businessUnit', 'choice', [
            'label'      => 'Select business unit',
            'choices'    => $choices,
            'multiple'   => false,
            'expanded'   => true,
            'label_attr' => ['class' => 'no-block'],
            'data'       => $selected ? array_search($selected, $choices) : null,
        ]);

        return $this;
    }
}
