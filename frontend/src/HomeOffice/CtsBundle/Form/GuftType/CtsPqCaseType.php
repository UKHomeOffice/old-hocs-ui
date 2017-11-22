<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Service\PQLordsMinister;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Service\PQHouse;
use HomeOffice\ListBundle\Service\ListHandler;

/**
 * Class CtsPqCaseType
 * @package HomeOffice\CtsBundle\Form\Type
 */
class CtsPqCaseType extends CtsCaseType
{
    use ReplyToMember;

    /**
     *
     * @param string $formPurpose
     * @param ListHandler $ctsListHandler
     * @param CTSHelper $ctsHelper
     * @param bool $cascadeValidation
     */
    public function __construct($formPurpose, $ctsListHandler, $ctsHelper, $cascadeValidation = false)
    {
        parent::__construct($formPurpose, $ctsListHandler, $ctsHelper, $cascadeValidation);
    }

    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->buildReplyToMemberForm($builder);

        /** @var CtsPqCase $ctsPqCase*/
        $ctsPqCase = $builder->getData();
        $isGroupedSlave = false;
        $memberList = $this->populateAnsweringMinisterNameList($ctsPqCase);

        if ($ctsPqCase != null) {
            $isGroupedSlave = $ctsPqCase->getIsGroupedSlave();
            $groupedCases = $ctsPqCase->getGroupedCases();
            $memberList = $this->ctsHelper->handleLegacyValue($memberList, $ctsPqCase->getMember());
        }

        $builder
            ->add('uin', 'text', [
                'label' => 'UIN',
                'required'  => false,
                'disabled' => true
            ])
            ->add('opDate', 'date', [
                'label' => 'OP date',
                'attr' => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ])
            ->add('woDate', 'date', [
                'label' => 'WO date',
                'attr' => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ])
            ->add('questionNumber', 'text', [
                'label' => 'Question No.',
            ])
            ->add('questionText', 'textarea', [
                'attr' => ['class' => 'form-control'],
                'label' => 'Text'
            ])
            ->add('roundRobin', 'choice', [
                'choices' => [true => 'Yes', false => 'No'],
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'inline'],
                'label' => 'Round Robin',
                'label_attr' => ['class' => 'block-label']
            ])
            ->add('cabinetOfficeGuidance', 'choice', [
                'choices' => ['Yes' => 'Yes', 'Pending' => 'Pending', 'N/A' => 'N/A'],
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'inline'],
                'label' => 'Cabinet Office guidance',
                'label_attr' => ['class' => 'block-label inline']
            ])
            ->add('answerText', 'textarea', [
                'attr' => ['class' => 'form-control'],
                'label' => 'Text'
            ])
            ->add('receivedType', 'choice', [
                'choices' => ['Direct' => 'Direct', 'Transfer' => 'Transfer'],
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'inline'],
                'label_attr' => ['class' => 'block-label inline']
            ])
            ->add('transferDepartmentName', 'text', [
                'label' => 'Department Name'
            ])
            ->add('draftResponseTarget', 'datetime', [
                'empty_value' => '-',
                'disabled' => true,
                'label' => 'Draft date'
            ])
            ->add('constituency', 'text')
            ->add('party', 'text')
            ->add('signedByHomeSec', 'checkbox', [
                'label' => 'Home secretary'
            ])
            ->add('reviewedByPermSec', 'checkbox', [
                'label' => 'Permanent secretary'
            ])
            ->add('answeringMinister', 'choice', [
                'choices' => $memberList,
                'empty_value' => 'Select Minister',
                'required'  => false,
                'label' => 'Answering Minister',
                'label_attr' => ['class' => 'form-label'], // @todo work out why this isn't working from the form theme
                'attr' => ['class' => 'form-control']
            ])
            ->add('answeringMinisterId', 'hidden')
            ->add('reviewedBySpads', 'checkbox', [
                'label' => 'Special advisor'
            ]);

        if (!$isGroupedSlave) {
            $builder
            ->add('uinsToGroup', 'text', [
                'label' => "UINs",
                'attr' => [
                    'class' => "grouped-uins-class"
                ]
            ])
            ->add('addGroupedCases', 'submit', [
                'label' => "Group",
                'attr' => ['class' => 'button']
            ]);
        }
        if (isset($groupedCases)) {
            foreach ($groupedCases as $idx => $groupedCase) {
                $builder
                ->add('removeGroupedCase_'.$groupedCase->getNodeId(), 'submit', [
                    'label' => 'Remove',
                    'attr' => ['class' => 'button']
                ]);
            }
        }

        if ('LPQ' == $ctsPqCase->getCorrespondenceType()) {
            $builder->add(
                'lordsMinister',
                'choice',
                [
                    'choices' => $this->populateLordsMinisterNameList(),
                    'empty_value' => "Select Lord's Minister",
                    'required'  => false,
                    'label' => "Lord's Minister",
                ]
            );
        }

        if ($this->isRequiredTask($ctsPqCase->getCaseTask()) ||
            $this->isRequiredStatus($ctsPqCase->getCaseStatus())
        ) {
            $this->bindRequiredFormFields($builder);
        }
    }

    /**
     * @param CtsPqCase ctsPqCase
     * @return array
     */
    protected function populateAnsweringMinisterNameList($ctsPqCase)
    {
        $membersNameListArray = [];
        $pqHouse = $this->ctsHelper->getPQHouseFromUIN($ctsPqCase->getUin());
        foreach ($this->ctsListHandler->getList('ctsMemberList') as $member) {
            if ($pqHouse == PQHouse::HOUSE_OF_LORDS && $member->getIsLords()
                || $pqHouse == PQHouse::HOUSE_OF_COMMONS && !$member->getIsLords()) {
                $membersNameListArray[$member->getDisplayName()] = $member->getDisplayName();
            }
        }
        return $membersNameListArray;
    }

    /**
     * @return array
     */
    protected function populateLordsMinisterNameList()
    {
        return PQLordsMinister::getPQLordsMinisterArray();
    }

    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase',
            'empty_data' => new CtsPqCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ]);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsPqCase';
    }
}
