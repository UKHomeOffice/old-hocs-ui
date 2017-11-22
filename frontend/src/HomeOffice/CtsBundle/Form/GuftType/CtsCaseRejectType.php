<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseRejectType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseRejectType extends GuftFormType
{
    use CtsCaseTransitions;

    /**
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('returnReason', 'textarea', [
                'label'      => 'Reason for rejection',
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
        return 'CtsCaseReject';
    }
}
