<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DocumentType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class DocumentType extends AbstractType
{
    use Groups\Document;

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
     * @param string $workspace
     * @param string $store
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
        $this->document($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Document';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => CtsCaseDocument::class,
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
