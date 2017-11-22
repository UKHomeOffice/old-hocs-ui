<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseLinkedType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseLinkedType extends AbstractType
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
            ->add('hrnsToLink', 'text', [
                'label' => 'Enter case ID',
            ])
            ->add('addLinkedCase', 'submit', [
                'label' => 'Link',
                'attr'  => ['class' => 'button'],
            ])
            ->add('removeLinkedCase', 'submit', [
                'attr' => ['class' => 'hidden removeLinkedCase'],
            ])
            ->add('linkedCaseToRemove', 'hidden', [
                'mapped' => false,
                'attr'   => ['class' => 'linkedCaseToRemove'],
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseLinked';
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
                if ($form->get('addLinkedCase')->isClicked()) {
                    return ['Case_Linked'];
                }
            },
        ]);
    }
}
