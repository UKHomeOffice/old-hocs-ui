<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Service\CtsCaseMinuteQAOutcomesHelper;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;

class CtsCaseMinuteType extends AbstractType
{

    /*
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
            ->add(
                'minuteContent',
                'textarea',
                array(
                    'required'  => false,
                    'attr' => array(
                        'class' => "minute-content-class"
                    )
                )
            )
            ->add(
                'saveMinute',
                'submit',
                array(
                    'attr' => array(
                        'value' => 'Save minute',
                        'data-ajax-message' => 'Saving minute...',
                    )
                )
            );

        if (null !== $this->task) {
            $builder->add(
                'task',
                'hidden',
                array(
                    'data' => $this->task
                )
            )
                ->add(
                    'minuteQaReviewOutcomes',
                    'choice',
                    array(
                        'choices'     => CtsCaseMinuteQAOutcomesHelper::getCaseMinuteQAOutcomes(),
                        'label'       => 'Quality Review Outcomes',
                        'multiple'    => true,
                        'empty_value' => '-',
                        'attr'        => array(
                            "class"            => "chosen-container-multi",
                            "data-placeholder" => "Select Quality Review Outcome"
                        )
                    )
                );
        }

        if ($this->caseId != null) {
            $builder->add(
                'id',
                'hidden',
                array(
                    'data' => $this->caseId,
                    'mapped' => false
                )
            );
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute',
            'empty_data' => new CtsCaseMinute()
        ));
    }

    public function getName()
    {
        return 'ctsCaseMinuteType';
    }
}
