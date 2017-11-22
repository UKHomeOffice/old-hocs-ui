<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DraftType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocumentRemove;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseLinked;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTopics;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsNo10CaseDraftType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\DraftType
 */
class CtsNo10CaseDraftType extends GuftFormType
{
    use CtsCaseAllocate, CtsCaseTransitions, CtsCaseLinked, CtsCaseTopics;
    use Document;
    use DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);

        if ($this->isEditable($builder->getData())) {
            $this->documentForm($builder)->documentRemoval($builder);
        }

        $case = $builder->getData();

        if ($case->getCorrespondenceType() === 'DTEN') {
            if ($this->isEditable($case)) {
                $builder->add('save', 'submit', [
                    'attr' => ['class' => 'button button-secondary'],
                ]);
            }
        } else {
            $this->buildTopicsForm($builder);
            $this->buildLinkedCaseForm($builder);
        }

        if ($this->isEditable($case)) {
            $this->buildAllocateForm($builder);
        }

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
        return 'CtsNo10CaseDraft';
    }

}
