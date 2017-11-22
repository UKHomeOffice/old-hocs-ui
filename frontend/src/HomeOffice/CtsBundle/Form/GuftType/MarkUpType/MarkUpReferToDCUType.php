<?php

namespace HomeOffice\CtsBundle\Form\GuftType\MarkUpType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MarkUpReferToDCUType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class MarkUpReferToDCUType extends GuftFormType
{

    /**
     * @var CTSHelper
     */
    protected $ctsHelper;

    /**
     * @var ListHandler
     */
    protected $listHandler;

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

        $builder->add('ogdName', 'hidden', [
            'label' => 'Department name',
            'data' => 'DCU'
        ]);

        $builder->add('closeRefer', 'submit', [
            'label' => 'Close Case',
            'attr' => ['class' => 'button'],
        ]);

        if ($this->markupUnitActive($case)) {
            $unitList = $this->ctsHelper->handleLegacyValue(
                $this->listHandler->getList('ctsUnitList'),
                $case->getMarkupUnit()
            );

            $builder->add('markupUnit', 'choice', [
                'choices'     => $unitList,
                'empty_value' => 'Select answering unit',
                'required'    => false,
                'label'       => 'Answering unit',
                'attr'        => ['class' => 'markup-unit'],
                'disabled'    => !in_array($case->getCaseStatus(), ['New', 'Draft']),
                'mapped'      => false,
            ]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkUpReferToDCU';
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
            'validation_groups'  => function(FormInterface $form) {
                $clickedButton = $this->getClickedButton($form)->getName();

                if ($clickedButton === 'closeRefer') {
                    return ['Markup-ReferToDCU'];
                }
            },
        ]);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupUnitActive(CtsCase $case)
    {
        return in_array($case->getShortName(), ['CtsUkviCase']);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function markupMinisterActive(CtsCase $case)
    {
        return in_array($case->getCorrespondenceType(), ['IMCM']);
    }
}
