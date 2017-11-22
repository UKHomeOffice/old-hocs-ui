<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CtsCaseStandardLineType extends AbstractType
{
 
    /**
     *
     * @var string
     */
    private $workspace;
 
    /**
     *
     * @var string
     */
    private $store;
 
    /**
     *
     * @var string
     */
    private $formPurpose;
 
    /**
     *
     * @var CtsListHandler
     */
    private $ctsListHandler;
 
    /**
     *
     * @var CtsHelper
     */
    private $ctsHelper;
 
    /**
     *
     * @param string $workspace
     * @param string $store
     * @param CtsListHandler $ctsListHandler
     * @param CtsHelper $ctsHelper
     */
    public function __construct($workspace, $store, $formPurpose, $ctsListHandler, $ctsHelper)
    {
        $this->workspace = $workspace;
        $this->store = $store;
        $this->formPurpose = $formPurpose;
        $this->ctsListHandler = $ctsListHandler;
        $this->ctsHelper = $ctsHelper;
    }
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $standardLine = $builder->getData();
     
        $unitList = $this->ctsListHandler->getList('ctsUnitList');
        $topicList = $this->ctsHelper->handleTopicList($this->ctsListHandler->getList('ctsTopicList'));
     
        if ($standardLine != null) {
            $unitList = $this->ctsHelper->handleLegacyValue($unitList, $standardLine->getAssociatedUnit());
            $topicList = $this->ctsHelper->handleLegacyValue(
                $topicList,
                $standardLine->getAssociatedTopic()
            );
        }
     
        $builder
        ->add('id', 'hidden')
        ->add('associatedUnit', 'choice', array(
            'choices' => $unitList,
            'empty_value' => 'Select unit',
            'required'  => false,
            'label' => 'Unit',
            'attr' => array(
                'class' => 'associated-unit'
            )
        ))
        ->add('associatedTopic', 'choice', array(
            'choices' => $topicList,
            'empty_value' => 'Select topic',
            'required'  => false,
            'label' => 'Topic',
            'multiple' => false,
            'attr' => array(
                'class' => 'associated-topic'
            )
        ));
     
        if ($this->formPurpose == 'create' || $this->formPurpose == 'edit') {
            $builder
            ->add('reviewDate', 'date', array('empty_value' => '-'));
        }
     
     
        if ($this->formPurpose == 'create') {
            $builder
            ->add('file', 'file', array( 'label' => 'File'))
            ->add('saveStandardLineButton', 'submit', array( 'label' => 'Add standard line' ));
        }
        
        if ($this->formPurpose == 'edit') {
            $builder
            ->add('file', 'file', array( 'label' => 'New version'))
            ->add('saveStandardLineButton', 'submit', array( 'label' => 'Save updates' ))
            ->add('uploadVersionButton', 'submit', array( 'label' => 'Upload version'));
        }
     
        if ($this->formPurpose == 'search') {
            $builder
            ->setMethod('get')
            ->add('search', 'submit', array('label' => 'Search'))
            ->add('name', 'text', array(
                'required'  => false,
                'label' => 'Name'
            ));
        }
    }

    public function getName()
    {
        return 'ctsCaseStandardLine';
    }
}
