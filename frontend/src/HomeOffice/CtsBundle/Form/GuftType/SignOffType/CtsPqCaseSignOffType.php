<?php

namespace HomeOffice\CtsBundle\Form\GuftType\SignOffType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsPqCaseSignOffType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsPqCaseSignOffType extends GuftFormType
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
        $builder
            ->add('answerText', 'textarea', [
                'label'      => 'Answer',
                'label_attr' => ['class' => 'hidden'],
                'attr'       => [
                    'class' => 'form-control form-control-full answerTextEdit',
                    'rows'  => '6',
                ],
            ])
            ->add('editAnswer', 'submit', [
                'attr' => ['class' => 'hidden editAnswer'],
            ]);

        $this->buildTransitionsForm($builder);
        $this->documentForm($builder);
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
        return 'CtsPqCaseSignOff';
    }
}
