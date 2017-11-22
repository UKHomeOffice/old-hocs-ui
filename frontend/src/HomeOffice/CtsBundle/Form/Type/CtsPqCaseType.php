<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Service\PQLordsMinister;
use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Service\PQHouse;

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
     * @param CtsListHandler $ctsListHandler
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
            ->add('uin', 'text', array(
                'label' => 'UIN',
                'required'  => false,
                'disabled' => true
            ))
            ->add('opDate', 'date', array(
                'label' => 'OP date',
                'empty_value' => '-'
            ))
            ->add('woDate', 'date', array(
                'label' => 'WO date',
                'empty_value' => '-'
            ))
            ->add('questionNumber', 'text', array(
                'label' => 'Question No.',
            ))
            ->add('questionText', 'textarea', array(
                'attr' => array('class' => 'plain'),
                'label' => 'Text'
            ))
            ->add('roundRobin', 'choice', array(
                'choices' => array(true => 'Yes', false => 'No'),
                'multiple' => false,
                'expanded' => true,
                'label' => 'Round Robin',
            ))
            ->add('cabinetOfficeGuidance', 'choice', array(
                'choices' => array('Yes' => 'Yes', 'Pending' => 'Pending', 'N/A' => 'N/A'),
                'multiple' => false,
                'expanded' => true,
                'label' => 'Cabinet Office guidance',
            ))
            ->add('answerText', 'textarea', array(
                'attr' => array('class' => 'plain'),
                'label' => 'Text'
            ))
            ->add('receivedType', 'choice', array(
                'choices' => array('Direct' => 'Direct', 'Transfer' => 'Transfer'),
                'multiple' => false,
                'expanded' => true
            ))
            ->add('transferDepartmentName', 'text', array(
                'label' => 'Department Name'
            ))
            ->add('draftResponseTarget', 'datetime', array(
                'empty_value' => '-',
                'disabled' => true,
                'label' => 'Draft date'
            ))
            ->add('constituency', 'text')
            ->add('party', 'text')
            ->add('signedByHomeSec', 'checkbox', array(
                'label' => 'Home Sec'
            ))
            ->add('reviewedByPermSec', 'checkbox', array(
                'label' => 'Perm Sec'
            ))
            ->add('answeringMinister', 'choice', array(
                'choices' => $memberList,
                'empty_value' => 'Select Minister',
                'required'  => false,
                'label' => 'Answering Minister',
            ))
            ->add('answeringMinisterId', 'hidden')
            ->add('reviewedBySpads', 'checkbox', array(
                'label' => 'SpAds'
            ));

        if (!$isGroupedSlave) {
            $builder
            ->add('uinsToGroup', 'text', array(
                'label' => "UINs",
                'attr' => array(
                    'class' => "grouped-uins-class"
                )
            ))
            ->add('addGroupedCases', 'submit', array(
                'label' => "Group"
            ));
        }
        if (isset($groupedCases)) {
            foreach ($groupedCases as $idx => $groupedCase) {
                $builder
                ->add('removeGroupedCase_'.$groupedCase->getNodeId(), 'submit', array(
                    'label' => 'Remove'
                ));
            }
        }

        if ('LPQ' == $ctsPqCase->getCorrespondenceType()) {
            $builder->add(
                'lordsMinister',
                'choice',
                array(
                    'choices' => $this->populateLordsMinisterNameList(),
                    'empty_value' => "Select Lord's Minister",
                    'required'  => false,
                    'label' => "Lord's Minister",
                )
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
        $membersNameListArray = array();
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
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase',
            'empty_data' => new CtsPqCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
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
