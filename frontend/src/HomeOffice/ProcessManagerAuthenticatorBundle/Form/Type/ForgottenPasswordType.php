<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgottenPasswordType extends AbstractType
{
 
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', 'text', array(
            'label' => 'Email Address',
            'constraints' => array(
                new NotBlank(),
            ),
        ))
        ->add('forgottenPasswordUsernameSubmit', 'submit', array(
            'label' => 'Submit',
            'attr' => ['class' => 'button'],
        ));
    }
 
    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'forgottenPassword';
    }
}
