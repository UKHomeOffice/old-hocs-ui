<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupTopics
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupTopics
{
    use Elements\MarkupTopic;
    use Elements\SecondaryTopic;

    /**
     * @param FormBuilderInterface $builder
     * @param TopicService        $topicService
     * @param bool                $standardLines
     *
     * @return static
     */
    protected function markupTopics(FormBuilderInterface $builder, TopicService $topicService, $standardLines = false)
    {
        $topics = $topicService->getTopicsForForm(
            CaseCorrespondenceSubType::getCaseType($builder->getData()->getCorrespondenceType())
        );

        $this
            ->markupTopic($builder, $topics, $standardLines)
            ->secondaryTopic($builder, $topics, $standardLines)
        ;

        return $this;
    }
}
