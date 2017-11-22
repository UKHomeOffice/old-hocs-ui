<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DraftType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CtsPqCaseDraftType extends GuftFormType
{
    use CtsCaseTransitions, CtsCaseDocument, CtsCaseAllocate;
    use Document;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answerText', 'textarea', [
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
        if ($this->isEditable($builder->getData())) {
            $this->documentForm($builder);
        }
        $this->buildAllocateForm($builder);

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
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsPqCaseDraft';
    }
}
