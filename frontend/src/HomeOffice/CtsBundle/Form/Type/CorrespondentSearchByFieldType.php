<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CorrespondentSearchByFieldType extends AbstractType
{
 
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->setMethod('GET')
        ->add('dateCreatedFrom', 'date', array(
            'empty_value' => '-',
            'label' => 'From'
        ))
        ->add('dateCreatedTo', 'date', array(
            'empty_value' => '-',
            'label' => 'To'
        ))
        ->add('replyForename', 'text', array(
            'required'  => false,
            'label' => 'Forename'
        ))
        ->add('replySurname', 'text', array(
            'required'  => false,
            'label' => 'Surname'
        ))
        ->add('postcode', 'text', array(
            'required'  => false,
            'label' => 'Postcode'
        ))
        ->add('email', 'text', array(
            'required'  => false,
            'label' => 'Email'
        ))
        ->add('searchButton', 'submit', array(
            'label' => 'Search',
            'attr' => array(
            ),
        ))
        ->add('clear', 'submit');

    }
 
    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }
 
    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'correspondentSearchByField';
    }
}
