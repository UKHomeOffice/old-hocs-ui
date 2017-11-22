<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Service\CaseDocumentTypeHelper;
use HomeOffice\CtsBundle\Form\DataTransformer\CaseToDocumentTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;

/**
 * Class CtsCaseDocumentType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseDocumentType extends AbstractType
{
    /**
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /**
     * Constructor
     *
     * @param string                 $workspace
     * @param string                 $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documentType', 'choice', [
                'choices'     => CaseDocumentTypeHelper::getAvailableTypesForCase($builder->getData()),
                'empty_value' => '',
                'label_attr'  => ['class' => 'form-label'],
                'attr'        => [
                    'class'            => 'chosen form-control',
                    'data-placeholder' => 'Select document type'
                ],
            ])
            ->add('documentDescription', 'textarea', [
                'label'      => 'Description (Optional)',
                'label_attr' => ['class' => 'form-label'],
                'attr'       => [
                    'class' => 'form-control form-control-full',
                    'rows'  => 4,
                ],
            ])
            ->add('file', 'file', [
                'label_attr' => ['class' => 'hidden'],
                'attr'       => ['class' => 'document-upload-class hidden'],
            ])
            ->add('addDocument', 'submit', [
                'attr' => ['class' => 'button-secondary btn'],
            ]);

        $builder->addModelTransformer(new CaseToDocumentTransformer());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument',
            'empty_data'        => new CtsCaseDocument($this->workspace, $this->store),
            'attr'              => ['novalidate' => 'novalidate'],
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('addDocument')->isClicked()) {
                    return ['Case_Document'];
                }
            },
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseDocument';
    }
}
