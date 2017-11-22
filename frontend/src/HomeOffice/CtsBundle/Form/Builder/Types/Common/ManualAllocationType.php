<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ManualAllocationType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class ManualAllocationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('assignedUnit', 'hidden', ['attr' => ['class' => 'assignedUnit']]);
        $builder->add('assignedTeam', 'hidden', ['attr' => ['class' => 'assignedTeam']]);
        $builder->add('assignedUser', 'hidden', ['attr' => ['class' => 'assignedUser']]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ManualAllocation';
    }
}
