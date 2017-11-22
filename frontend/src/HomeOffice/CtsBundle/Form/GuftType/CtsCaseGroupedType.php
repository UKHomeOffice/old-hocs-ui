<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseGroupedType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseGroupedType extends AbstractType
{
    /**
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /**
     * Constructor
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uinsToGroup', 'text', [
                'label' => 'Enter UIN',
                'attr'  => ['class' => 'grouped-uins-class'],
            ])
            ->add('addGroupedCase', 'submit', [
                'label' => 'Group',
                'attr'  => ['class' => 'button'],
            ])
            ->add('removeGroupedCase', 'submit', [
                'attr' => ['class' => 'hidden removeGroupedCase'],
            ])
            ->add('groupedCaseToRemove', 'hidden', [
                'mapped' => false,
                'attr'   => ['class' => 'groupedCaseToRemove'],
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseGrouped';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'empty_data'        => new CtsCase($this->workspace, $this->store),
            'attr'              => ['novalidate' => 'novalidate'],
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('addGroupedCase')->isClicked()) {
                    return ['Case_Grouped'];
                }
            },
        ]);
    }
}
