<?php

namespace HomeOffice\CtsBundle\Form\GuftType\SignOffType;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocumentRemove;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsUkviCaseSignOffType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsUkviCaseSignOffType extends GuftFormType
{
    use CtsCaseTransitions;
    use DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);
        $this->documentRemoval($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'attr'       => ['novalidate' => 'novalidate'],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsUkviCaseSignOff';
    }
}
