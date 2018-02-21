<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\AlfrescoApiBundle\Service\TopicUnits;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsStandardLineSearchType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsStandardLineSearchType extends AbstractType
{
    use Elements\AssociatedUnit;
    use Elements\AssociatedTopic;
    use ELements\StandardLineName;
    use Elements\Save;

    /**
     * @var ListService
     */
    protected $listService;

    /**
     * @var TopicService
     */
    protected $topicService;

    /**
     * Constructor
     *
     * @param ListService  $listService
     * @param TopicService $topicService
     */
    public function __construct(ListService $listService, TopicService $topicService)
    {
        $this->listService = $listService;
        $this->topicService = $topicService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->associatedUnit($builder, TopicUnits::getTopicUnitList())
            ->associatedTopic($builder, $this->topicService->getTopicsForForm())
            ->standardLineName($builder)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '';
    }
}
