<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LinkedCaseType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class LinkedCaseType extends AbstractType
{
    use Groups\LinkedCase;

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
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->linkedCase($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LinkedCase';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => CtsCase::class,
            'empty_data'        => new CtsCase($this->workspace, $this->store),
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('addLinkedCase')->isClicked()) {
                    return ['Case_Linked'];
                }
            },
        ]);
    }
}
