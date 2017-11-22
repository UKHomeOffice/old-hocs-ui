<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DispatchType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use HomeOffice\AlfrescoApiBundle\Entity\Member;
use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CaseMemberList;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsPqCaseDispatchType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\DispatchType
 */
class CtsPqCaseDispatchType extends GuftFormType
{
    use CaseMemberList;
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
            ])
            ->add('answeringMinister', 'choice', [
                'choices'     => $this->getMinisterList($builder->getData()),
                'empty_value' => '',
                'attr' => [
                    'class' => 'chosen',
                    'data-placeholder' => 'Select member',
                ],
            ]);

        $this->buildTransitionsForm($builder);
        $this->documentForm($builder);
        $this->documentRemoval($builder);

        // Validate AllocateTo here as it is an unmapped field
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $this->updateAnsweringMinisterId($event->getData());
        });
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
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsPqCaseDispatch';
    }

    /**
     * @param CtsPqCase $case
     */
    private function updateAnsweringMinisterId(CtsPqCase $case)
    {
        /** @var Member $member */
        foreach ($this->listHandler->getList('ctsMemberList') as $member) {
            if ($member->getDisplayName() == $case->getAnsweringMinister()) {
                $case->setAnsweringMinisterId($member->getMemberId());
                break;
            }
        }
    }
}
