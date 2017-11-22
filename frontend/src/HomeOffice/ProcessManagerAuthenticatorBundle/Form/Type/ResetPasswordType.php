<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
 
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('currentPassword', 'password', array(
            'label' => 'Old password',
            'constraints' => array(
                new NotBlank(),
            ),
        ))
        ->add('newPassword', 'repeated', array(
            'type' => 'password',
            'constraints' => array(
                new NotBlank(),
            ),
            'invalid_message' => 'The password fields must match.',
            'first_options'  => array('label' => 'New password'),
            'second_options' => array('label' => 'Confirm password'),
        ))
        ->add('SaveNewPassword', 'submit', array(
            'label' => 'Save new password',
            'attr' => ['class' => 'button'],
        ));
    }
 
    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'resetPassword';
    }
}
