<?php

namespace HomeOffice\CtsBundle\Form\GuftType\SignOffType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsNo10CaseSignOffType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsNo10CaseSignOffType extends GuftFormType
{
    use CtsCaseTransitions;
    use Document;
    use DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);
        $this->documentRemoval($builder);

        if (in_array($builder->getData()->getCaseTask(), ['Private Office approval', 'HS Private Office approval'])) {
            $this->documentForm($builder);
        }
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
        return 'CtsNo10CaseSignOff';
    }
}