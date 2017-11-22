<?php

namespace HomeOffice\CtsBundle\Form\GuftType\ApproveType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\PQLordsMinister;
use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocumentRemove;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsPqCaseApproveType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\ApproveType
 */
class CtsPqCaseApproveType extends GuftFormType
{
    use CtsCaseTransitions;
    use Document;
    use DocumentRemoval;

    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * Constructor
     *
     * @param string      $workspace
     * @param string      $store
     * @param ListHandler $listHandler
     */
    public function __construct($workspace, $store, ListHandler $listHandler)
    {
        parent::__construct($workspace, $store);

        $this->listHandler = $listHandler;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

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

        if ($case->getCaseTask() === 'Parly approval') {
            $builder->add('markupMinister', 'choice', [
                'label'       => 'Sign off Minister',
                'empty_value' => '',
                'choices'     => $this->listHandler->getList('ctsMinisterList'),
                'disabled'    => $case->getCaseTask() !== 'Parly approval',
                'attr'        => [
                    'class'            => 'chosen',
                    'data-placeholder' => 'Select Minister',
                ],
            ]);

            if ($case->getCorrespondenceType() === 'LPQ') {
                $builder->add('lordsMinister', 'choice', [
                    'label' => 'Lord\'s Minister',
                    'empty_value' => '',
                    'choices' => PQLordsMinister::getPQLordsMinisterArray(),
                    'disabled' => $case->getCaseTask() !== 'Parly approval',
                    'attr'        => [
                        'class'            => 'chosen',
                        'data-placeholder' => 'Select Lord\'s Minister',
                    ],
                ]);
            }
        }

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
        return 'CtsPqCaseApprove';
    }
}
