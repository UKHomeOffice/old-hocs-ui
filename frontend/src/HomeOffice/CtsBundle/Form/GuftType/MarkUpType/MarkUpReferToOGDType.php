<?php

namespace HomeOffice\CtsBundle\Form\GuftType\MarkUpType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkUpReferToOGDType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class MarkUpReferToOGDType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ogdName', 'text', [
            'label' => 'Department name',
        ]);

        $builder->add('closeRefer', 'submit', [
            'label' => 'Close Case',
            'attr' => ['class' => 'button'],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkUpReferToOGD';
    }
}
