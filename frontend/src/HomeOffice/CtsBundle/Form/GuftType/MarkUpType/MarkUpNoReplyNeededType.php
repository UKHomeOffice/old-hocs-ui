<?php

namespace HomeOffice\CtsBundle\Form\GuftType\MarkUpType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkUpNoReplyNeededType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class MarkUpNoReplyNeededType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('markupCancelDetails', 'textarea', [
            'label' => 'Further details (optional)',
            'mapped' => false,
            'attr' => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
            ],
        ]);

        $builder->add('closeCancel', 'submit', [
            'label' => 'Close Case',
            'attr' => ['class' => 'button'],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkUpNoReplyNeeded';
    }
}
