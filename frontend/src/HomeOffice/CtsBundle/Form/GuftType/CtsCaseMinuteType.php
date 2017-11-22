<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Service\CtsCaseMinuteQAOutcomesHelper;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseMinuteType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseMinuteType extends AbstractType
{
    /**
     * @var string
     */
    private $caseId;

    /**
     * @var string
     */
    protected $task;

    /**
     * @param string $caseId
     * @param string $task
     */
    public function __construct($caseId = null, $task = null)
    {
        $this->caseId = $caseId;
        $this->task = $task;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('minuteContent', 'textarea', [
                'required'  => false,
                'attr'      => [
                    'class' => 'form-control form-control-full minute-content-class reset',
                    'rows'  => 6,
                ],
            ])
            ->add('saveMinute', 'submit', [
                'label' => 'Add',
                'attr'  => ['class' => 'button'],
            ]);

//        if (null !== $this->task) {
//            $builder
//                ->add('task', 'hidden', [
//                    'data' => $this->task
//                ])
//                ->add('minuteQaReviewOutcomes', 'choice', [
//                    'choices'     => CtsCaseMinuteQAOutcomesHelper::getCaseMinuteQAOutcomes(),
//                    'label'       => 'Quality Review Outcomes',
//                    'multiple'    => true,
//                    'empty_value' => '-',
//                    'attr'        => [
//                        'class'            => 'chosen-container-multi',
//                        'data-placeholder' => 'Select Quality Review Outcome',
//                    ]
//                ]);
//        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute',
            'empty_data' => new CtsCaseMinute()
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseMinute';
    }
}
