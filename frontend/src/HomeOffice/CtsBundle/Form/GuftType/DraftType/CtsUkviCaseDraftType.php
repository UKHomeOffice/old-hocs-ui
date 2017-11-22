<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DraftType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseLinked;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTopics;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;

/**
 * Class CtsUkviCaseDraftType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\DraftType
 */
class CtsUkviCaseDraftType extends GuftFormType
{
    use CtsCaseAllocate;
    use CtsCaseTransitions;
    use CtsCaseLinked;
    use CtsCaseTopics;
    use Document;
    use DocumentRemoval;

    private $channelExclusions = [ HmpoResponse::LETTER, HmpoResponse::FAX ];

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getData()->getCorrespondenceType() === 'IMCB') {
            $this->channelExclusions[] = HmpoResponse::OUTREACH;
        }

        $builder
            ->add('hmpoResponse', 'choice', array(
                'label'      => 'Response channel',
                'choices'    => HmpoResponse::filterConstants(
                    HmpoResponse::getAll(true),
                    $this->channelExclusions
                ),
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label_attr' => ['class' => 'block-label inline']
            ));

        if ($this->isEditable($builder->getData())) {
            $builder->add('save', 'submit', [
                'attr' => ['class' => 'button button-secondary'],
            ]);
        }

        $this->buildTransitionsForm($builder);
        if ($this->isEditable($builder->getData())) {
            $this->documentForm($builder);
            $this->documentRemoval($builder);
        }
        $this->buildAllocateForm($builder);
        $this->buildLinkedCaseForm($builder);
        $this->buildTopicsForm($builder);

        $this->applyReadOnly($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'attr'               => ['novalidate' => 'novalidate'],
            'cascade_validation' => true,
            'validation_groups'  => function(FormInterface $form) {
                $clickedButton = $this->getClickedButton($form);
                if (!is_null($clickedButton) && (
                        $clickedButton->getName() === 'SendForQA' || $clickedButton === 'SendToDispatch'
                    )
                ) {
                    return ['Case_Draft'];
                }
            },
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsUkviCaseDraft';
    }

}
