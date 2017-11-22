<?php

namespace HomeOffice\CtsBundle\Form\GuftType\MarkUpType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTopics;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MarkUpAllocateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class MarkUpAllocateType extends GuftFormType
{
    use CtsCaseTopics;

    /**
     * @var CTSHelper
     */
    protected $ctsHelper;

    /**
     * @var ListHandler
     */
    protected $listHandler;

    /**
     * @var Unit[]
     */
    private $unitList;

    /**
     * Constructor
     *
     * @param string      $workspace
     * @param string      $store
     * @param CTSHelper   $ctsHelper
     * @param ListHandler $listHandler
     */
    public function __construct($workspace, $store, CTSHelper $ctsHelper, ListHandler $listHandler)
    {
        parent::__construct($workspace, $store);

        $this->ctsHelper = $ctsHelper;
        $this->listHandler = $listHandler;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        if ($this->markupUnitsActive($case)) {
            $unitList = $this->ctsHelper->handleLegacyValue(
                $this->listHandler->getList('ctsUnitList'),
                $case->getMarkupUnit()
            );

            $builder->add('markupUnit', 'choice', [
                'choices'     => $unitList,
                'empty_value' => 'Select answering unit',
                'required'    => false,
                'label'       => 'Answering unit',
                'attr'        => [
                    'class'            =>  'chosen markup-unit',
                    'data-placeholder' => 'Select answering unit'
                ],
                'disabled'    => !in_array($case->getCaseStatus(), ['New', 'Draft']),
            ]);
        }

        if ($this->markupMinisterActive($case)) {
            $builder->add('markupMinister', 'choice', [
                'choices'     => $this->listHandler->getList('ctsMinisterList'),
                'required'    => false,
                'empty_value' => '',
                'label'       => 'Sign off Minister',
                'attr'        => [
                    'class'            => 'chosen',
                    'data-placeholder' => 'Select Minister'
                ],
            ]);
        }

        if ($this->markupTeamActive($case)) {
            $builder->add('markupTeam', 'choice', [
                'choices'     => $this->getTeamsForUnit($case->getMarkupUnit()),
                'empty_value' => '',
                'required'    => false,
                'label'       => 'Team',
                'mapped'      => false,
                'disabled'    => 'disabled',
                'attr'        => [
                    'class'            => 'chosen markup-team',
                    'data-placeholder' => 'Select team'
                ],
            ]);

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();

                if (!is_null($data)) {
                    $form->add('markupTeam', 'choice', [
                        'choices' => $this->getTeamsForUnit($data['markupUnit']),
                    ]);
                }
            });
        }

        if ($this->markupTopicsActive($case)) {
            $this->buildTopicsForm($builder);
        }

        if ($this->markupRequiresReview($case)) {
            $builder
                ->add('signedByHomeSec', 'checkbox', [
                    'label' => 'Home secretary',
                    'disabled' => !in_array($case->getCaseStatus(), ['New', 'Draft']),
                ])
                ->add('reviewedByPermSec', 'checkbox', [
                    'label' => 'Permanent secretary',
                    'disabled' => !in_array($case->getCaseStatus(), ['New', 'Draft']),
                ])
                ->add('reviewedBySpads', 'checkbox', [
                    'label' => 'Special advisor',
                    'disabled' => !in_array($case->getCaseStatus(), ['New', 'Draft']),
                ]);
        }

        if ($this->markupExemptionsActive($case)) {
            $builder->add('exemptions', 'choice', [
                'choices'     => $case->getFoiIsEir() ?
                    FoiAssess::getEirPitQualifiedExemptions() : FoiAssess::getFoiPitQualifiedExemptions(),
                'multiple'    => true,
                'empty_value' => 'Select exemption(s)',
                'label'       => 'Exemptions',
                'label_attr'  => ['class' => 'form-label'],
                'attr'        => ['class' => 'form-control']
            ]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkUpAllocate';
    }

    /**
     * @param CtsCase $case
     * @return bool
     */
    protected function markupUnitsActive(CtsCase $case)
    {
        return ! in_array($case->getShortName(), ['CtsFoiComplaintCase']);
    }

    /**
     * @param CtsCase $case
     * @return bool
     */
    protected function markupExemptionsActive(CtsCase $case)
    {
         return in_array($case->getShortName(), ['CtsFoiCase']) && $case->getCaseTask() === 'Draft case';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'empty_data'         => new CtsCase($this->workspace, $this->store),
            'attr'               => ['novalidate' => 'novalidate'],
            'cascade_validation' => true,
        ]);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupMinisterActive(CtsCase $case)
    {
        return
            in_array($case->getShortName(), ['CtsDcuMinisterialCase', 'CtsNo10Case']) ||
            in_array($case->getCorrespondenceType(), ['IMCM']);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupTopicsActive(CtsCase $case)
    {
        return
            in_array($case->getShortName(), ['CtsDcuMinisterialCase', 'CtsDcuTreatOfficialCase', 'CtsFoiCase']) ||
            in_array($case->getCorrespondenceType(), ['DTEN']);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupRequiresReview(CtsCase $case)
    {
        return in_array($case->getShortName(), ['CtsPqCase']);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupTeamActive(CtsCase $case)
    {
        return
            in_array($case->getShortName(), [
                'CtsUkviCase', 'CtsNo10Case', 'CtsDcuMinisterialCase', 'CtsDcuTreatOfficialCase'
                ]) &&
            !in_array($case->getCorrespondenceType(), ['DTEN']);
    }

    /**
     * @param $validUnit
     *
     * @return array
     */
    private function getTeamsForUnit($validUnit)
    {
        $teams = [];

        foreach ($this->getUnitList() as $unit) {
            if ($unit->getAuthorityName() === $validUnit) {
                foreach ($unit->getTeams() as $team) {
                    $teams[$team->getAuthorityName()] = $team->getDisplayName();
                }
            }
        }

        return $teams;
    }

    /**
     * Get Unit List
     *
     * @return Unit[]
     */
    private function getUnitList()
    {
        if (is_null($this->unitList)) {
            $this->unitList = $this->listHandler->getList('ctsUnitAndTeamList');
        }

        return $this->unitList;
    }
}
