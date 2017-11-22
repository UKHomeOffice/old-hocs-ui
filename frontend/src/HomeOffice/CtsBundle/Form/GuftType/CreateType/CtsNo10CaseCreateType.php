<?php

namespace HomeOffice\CtsBundle\Form\GuftType\CreateType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseLinked;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseMarkUp;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseReplyTo;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\Components\HomeSecretaryReply;
use HomeOffice\CtsBundle\Form\GuftType\Components\ReplyToNumberTenCopy;
use HomeOffice\CtsBundle\Form\GuftType\CtsCaseCreateType;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsNo10CaseCreateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsNo10CaseCreateType extends GuftFormType
{
    use CtsCaseTransitions, CtsCaseLinked, CtsCaseDocument, CtsCaseReplyTo, CtsCaseMarkUp, CtsCaseAllocate,
        ReplyToNumberTenCopy, HomeSecretaryReply;
    use Document;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        $builder
            ->add('dateReceived', 'date', [
                'label'       => 'Date received',
                'attr'        => ['class' => 'datePicker todayButton pastOnly'],
                'empty_value' => '', // Required to stop the date picker defaulting to 01/01/2011
            ])
            ->add('dateOfLetter', 'date', [
                'label'       => 'Date of Letter',
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => '', // Required to stop the date picker defaulting to 01/01/2011
            ]);

        if ($case->getCorrespondenceType() === 'UTEN') {
            $builder->
            add('priority', 'checkbox', [
                'label' => 'Mark as priority',
            ]);
        }

        if ($this->isEditable($case)) {
            $builder->add('save', 'submit', [
                'attr' => ['class' => 'button button-secondary'],
            ]);
        }

        if ($case->getCorrespondenceType() === 'DTEN') {
            $builder->add('caseResponseDeadline', 'date', [
                'label' => 'Deadline date',
                'attr'  => ['class' => 'datePicker todayButton futureOnly'],
                'empty_value' => '', // Required to stop the date picker defaulting to 01/01/2011
            ])
            ->add('channel', 'choice', [
                'label'      => 'Original channel',
                'choices'    => Channel::filterConstants(Channel::getChannelArray(), [
                    Channel::LETTER, Channel::FAX, Channel::FURTHER_ACTION,
                ]),
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label_attr' => ['class' => 'block-label inline']
            ])
            ->add('draftDate', 'date', [
                'label'       => 'Drafting deadline',
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => ''
            ]);
        }

        $builder->add('replyTo', $builder->getData()->getShortName().'ReplyTo', [
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        $this->buildTransitionsForm($builder);
        $this->buildLinkedCaseForm($builder);
        if ($this->isEditable($case)) {
            $this->documentForm($builder);
        }
        $this->buildMarkUpCaseForm($builder);
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
            'validation_groups'  => function(FormInterface $form) {
                $clickedButton = $this->getClickedButton($form);
                if (!is_null($clickedButton) && $clickedButton->getName() === 'Next') {
                    $groups = ['Case_Create'];

                    if ($form->getData()->getCorrespondenceType() == 'DTEN') {
                        $groups = ['Case_Create_DTEN'];
                    };

                    return $groups;
                }
            },
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsNo10CaseCreate';
    }
}
