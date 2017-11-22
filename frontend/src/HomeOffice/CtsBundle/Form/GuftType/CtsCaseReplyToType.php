<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\CtsBundle\Form\GuftType\Components\CaseMemberList;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseReplyToType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseReplyToType extends GuftFormType
{
    use CaseMemberList;

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
        $builder->add('member', 'choice', [
            'choices'     => $this->getMemberList(),
            'empty_value' => '',
            'required'    => true,
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select member',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseReplyTo';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'empty_data' => new CtsCase($this->workspace, $this->store),
            'attr'       => ['novalidate' => 'novalidate'],
        ]);
    }
}
