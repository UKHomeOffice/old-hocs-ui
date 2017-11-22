<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseDraftType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseDraftType extends GuftFormType
{
    use CtsCaseTransitions, CtsCaseDocument, CtsCaseAllocate;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('answerText', 'textarea', [
            'label'      => 'Answer',
            'label_attr' => ['class' => 'hidden'],
            'attr'       => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
            ],
        ]);

        if ($this->isEditable($builder->getData())) {
            $builder->add('save', 'submit', [
                'attr' => ['class' => 'button button-secondary'],
            ]);
        }

        $this->buildTransitionsForm($builder);
        $this->buildAllocateForm($builder);
        $this->buildDocumentForm($builder);

        $this->applyReadOnly($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'attr'               => ['novalidate' => 'novalidate'],
            'cascade_validation' => true,
            'validation_groups'  => function(FormInterface $form) {
                $clickedButton = $this->getClickedButton($form);
                if (!is_null($clickedButton) && $clickedButton->getName() === 'SendForQA') {
                    return ['Case_Draft'];
                }
            },
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseDraft';
    }
}
