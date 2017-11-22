<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
use HomeOffice\AlfrescoApiBundle\Service\FoiExemptions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PitQualifiedExemptions
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PitQualifiedExemptions
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $foiIsEir
     *
     * @return static
     */
    public function pitQualifiedExemptions(FormBuilderInterface $builder, $foiIsEir = false)
    {
        $choices = FoiExemptions::getPitQualifiedExemptions($foiIsEir);

        $builder->add('pitQualifiedExemptions', 'choice', [
            'choices'     => array_combine($choices, $choices),
            'multiple'    => true,
            'empty_value' => '',
            'label'       => 'Which exemption(s) are you considering?',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'chosen-full form-control',
                'data-placeholder' => 'Select exemption(s)',
            ],
        ]);

        return $this;
    }
}
