<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Session\Session;

class FeatureToggleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $featureToggle = new ctsFeaturesToggle(new Session);

        $builder->add('featureToggle', 'choice', [
            'choices' => [
                true => 'Yes',
                false => 'No'
            ],
            'multiple' => false,
            'expanded' => true,
            'label' => false,
            'data' => $featureToggle->get()
        ])
        ->add('save', 'submit', ['attr' => ['class' => 'button']]);
    }

    public function getName()
    {
        return 'featureToggle';
    }
}
