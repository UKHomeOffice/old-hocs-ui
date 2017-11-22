<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseMarkUpType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseTopicsType extends AbstractType
{
    /**
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * Constructor
     *
     * @param string      $workspace
     * @param string      $store
     * @param TopicService $topicService
     */
    public function __construct($workspace, $store, TopicService $topicService)
    {
        $this->workspace = $workspace;
        $this->store = $store;
        $this->topicService = $topicService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $topicList = $this->topicService->getTopicsForForm(
            CaseCorrespondenceSubType::getCaseType($builder->getData()->getCorrespondenceType())
        );

        $builder->add('markupTopic', 'choice', [
            'choices'     => $topicList,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Topic',
            'attr'        => [
                'class'               => 'chosen markup-topic',
                'data-placeholder'    => 'Select topic',
                'data-standard-lines' => $this->showStandardLines($builder->getData()),
            ],
        ])->add('secondaryTopic', 'choice', [
            'choices'     => $topicList,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Second Topic',
            'attr'        => [
                'class'               => 'chosen markup-secondary-topic',
                'data-placeholder'    => 'Select secondary topic',
                'data-standard-lines' => $this->showStandardLines($builder->getData()),
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseTopics';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'empty_data'         => new CtsCase($this->workspace, $this->store),
            'attr'               => ['novalidate' => 'novalidate'],
            'cascase_validation' => true,
        ]);

    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function showStandardLines(CtsCase $case)
    {
        return in_array($case->getShortName(), ['CtsDcuMinisterialCase', 'CtsDcuTreatOfficialCase']);
    }
}
