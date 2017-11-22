<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DocumentRemovalType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class DocumentRemovalType extends AbstractType
{
    use Groups\DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->documentRemoval($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'DocumentRemoval';
    }
}
