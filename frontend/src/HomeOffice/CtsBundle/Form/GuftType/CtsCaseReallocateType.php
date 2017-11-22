<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseReallocateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseReallocateType extends CtsCaseAllocateType
{
    use CtsCaseTransitions;

    /**
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('reallocationReason', 'textarea', [
                'label'      => 'Reason for reallocation (optional)',
                'label_attr' => ['class' => 'form-label'],
                'attr'       => [
                    'class' => 'form-control form-control-full',
                    'rows'  => 4,
                ],
                'mapped'     => false,
            ]);

        $this->buildTransitionsForm($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseReallocate';
    }
}
