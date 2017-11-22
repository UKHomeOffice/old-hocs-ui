<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EmailPreferencesChildType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class EmailPreferencesChildType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'EmailPreferencesChild';
    }
}
