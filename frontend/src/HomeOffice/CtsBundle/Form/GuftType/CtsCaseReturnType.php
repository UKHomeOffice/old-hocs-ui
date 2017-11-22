<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseReturnType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseReturnType extends GuftFormType
{
    use CtsCaseTransitions;

    /**
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $case = $builder->getData();
        $optional = $case->getShortName() === 'CtsDcuMinisterialCase' ||
            $case->getShortName() === 'CtsNo10Case' ||
            $case->getShortName() === 'CtsDcuTreatOfficialCase' ? null : ' (optional)';

        $builder
            ->add('returnReason', 'textarea', [
                'label'      => 'Reason for return' . $optional,
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
        return 'CtsCaseReturn';
    }
}
