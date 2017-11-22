<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseDocumentRemoveType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseDocumentRemoveType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('removeDocument', 'submit', [
                'attr' => ['class' => 'hidden removeDocument'],
            ])
            ->add('documentToRemove', 'hidden', [
                'mapped' => false,
                'attr'   => ['class' => 'documentToRemove'],
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseDocumentRemove';
    }
}
