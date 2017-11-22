<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Exemptions
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Exemptions
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $foiIsEir
     */
    public function exemptions(FormBuilderInterface $builder, $foiIsEir = false)
    {
        $builder->add('exemptions', 'choice', [
            'choices'     => $foiIsEir ?
                FoiAssess::getEirPitQualifiedExemptions() : FoiAssess::getFoiPitQualifiedExemptions(),
            'multiple'    => true,
            'empty_value' => '',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'chosen-full form-control',
                'data-placeholder' => 'Select exemption(s)',
            ],
        ]);
    }
}
