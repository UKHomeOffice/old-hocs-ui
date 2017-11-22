<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;

class CtsCaseDocumentTemplateType extends AbstractType
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
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store, $formPurpose)
    {
        $this->workspace = $workspace;
        $this->store = $store;
        $this->formPurpose = $formPurpose;
    }
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $correspondenceMap = CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes();
     
        $builder
        ->add('appliesToCorrespondenceType', 'choice', array(
            'choices' => $correspondenceMap,
            'label' => 'Case type',
            'empty_value' => '-'
        ))
        ->add('templateName', 'text')
        ->add('validFromDate', 'date', array(
            'empty_value' => '-'
        ))
        ->add('validToDate', 'date', array(
            'empty_value' => '-'
        ));
        
        if ($this->formPurpose == 'create') {
            $builder
            ->add('file', 'file', array( 'label' => 'Template file'))
            ->add('saveTemplateButton', 'submit', array( 'label' => 'Add template' ));
        }
        
        if ($this->formPurpose == 'edit') {
            $builder
            ->add('saveTemplateButton', 'submit', array( 'label' => 'Save template' ));
        }
    }
 
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate',
            'empty_data' => new CtsCaseDocumentTemplate($this->workspace, $this->store)
        ));
    }

    public function getName()
    {
        return 'ctsCaseDocumentTemplate';
    }
}
