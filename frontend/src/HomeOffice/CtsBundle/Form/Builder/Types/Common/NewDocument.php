<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\Builder\Elements\AddDocument;
use HomeOffice\CtsBundle\Form\Builder\Elements\DocumentDescription;
use HomeOffice\CtsBundle\Form\Builder\Elements\DocumentType;
use HomeOffice\CtsBundle\Form\Builder\Elements\File;
use HomeOffice\CtsBundle\Form\DataTransformer\CaseToDocumentTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UploadDocument
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
class NewDocument extends AbstractType
{
    use DocumentType;
    use DocumentDescription;
    use File;
    use AddDocument;

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
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->documentType($builder);
        $this->documentDescription($builder);
        $this->file($builder);
        $this->addDocument($builder);

        $builder->addModelTransformer(new CaseToDocumentTransformer);
    }

    public function getName()
    {
        return 'NewDocument';
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
}

