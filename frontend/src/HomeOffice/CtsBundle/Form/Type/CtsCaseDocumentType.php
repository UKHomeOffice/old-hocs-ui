<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CaseDocumentType;

class CtsCaseDocumentType extends AbstractType
{
 
    /*
     * @var string
     */
    private $formPurpose;
 
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
 
    /*
     * @var string
     */
    private $caseId;
 
    /**
     *
     * @param string $formPurpose
     * @param string $workspace
     * @param string $store
     * @param string $caseId
     */
    public function __construct($formPurpose, $workspace, $store, $caseId = null)
    {
        $this->formPurpose = $formPurpose;
        $this->caseId = $caseId;
        $this->workspace = $workspace;
        $this->store = $store;
    }
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('documentType', 'choice', array(
            'choices' => CaseDocumentType::getCaseDocumentTypeArray(),
            'label' => 'Type *',
            'empty_value' => 'Select document type',
        ))
        ->add('documentDescription', 'textarea', array(
            'attr' => array('class' => 'plain'),
            'label' => 'Description *'
        ))
        ->add('file', 'file', array(
                'attr' => array(
                    'class' => "document-upload-class"
                ),
                'label' => 'File *',
            ));
     
        if ($this->caseId != null) {
            $builder->add('id', 'hidden', array('data' => $this->caseId, 'mapped' => false));
        }
     
        if ($this->formPurpose == 'edit' || $this->formPurpose == 'view') {
            $builder->add('addDocument', 'submit', array(
                'attr' => array(
                    'value' => 'Add document',
                    'data-ajax-message' => 'Uploading...',
                ),
            ));
        }
    }
 
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument',
            'empty_data' => new CtsCaseDocument($this->workspace, $this->store)
        ));
    }

    public function getName()
    {
        return 'ctsCaseDocument';
    }
}
