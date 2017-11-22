<?php

namespace HomeOffice\CtsBundle\Form\GuftType\MarkUpType;

use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkUpExemptionType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class MarkUpExemptionType extends GuftFormType
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
     * @param string $workspace
     * @param string $store
     * @param CTSHelper $ctsHelper
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
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $case = $builder->getData();

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

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkUpExemption';
    }

}
