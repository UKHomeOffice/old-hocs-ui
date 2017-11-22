<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseCompleteType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseCancelType extends GuftFormType
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
            ->add('cancelReason', 'textarea', [
                'label'      => 'Reason for cancelling the case',
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
        return 'CtsCaseCancel';
    }
}
