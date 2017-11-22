<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types;

use HomeOffice\AlfrescoApiBundle\Entity\Member;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\AlfrescoApiBundle\Service\TaskStatus;
use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\ListBundle\Service\ListHandler;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SuperSearch
 *
 * @package HomeOffice\CtsBundle\Form\Builder
 */
class SuperSearch extends AbstractType
{
    use Elements\BusinessUnit;
    use Elements\CaseId;
    use Elements\CreatedFrom;
    use Elements\CreatedTo;
    use Elements\DeadlineFrom;
    use Elements\DeadlineTo;
    use Elements\MarkupDecision;
    use Elements\MarkupUnit;
    use Elements\MarkupMinister;
    use Elements\Team;
    use Elements\MarkupTopic;
    use Elements\Status;
    use Elements\Task;

    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * @var ListService
     */
    protected $listService;

    /**
     * @var TopicService
     */
    protected $topicService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor
     *
     * @param ListHandler     $listHandler
     * @param ListService     $listService
     * @param TopicService    $topicService
     * @param RouterInterface $router
     */
    public function __construct(
        ListHandler $listHandler,
        ListService $listService,
        TopicService $topicService,
        RouterInterface $router
    ) {
        $this->listHandler = $listHandler;
        $this->listService = $listService;
        $this->topicService = $topicService;
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->businessUnit($builder)
            ->caseId($builder)
            ->createdFrom($builder)
            ->createdTo($builder)
            ->deadlineFrom($builder, null)
            ->deadlineTo($builder, null)
            ->markupDecision($builder, MarkupDecisions::getMarkupDecisionsArray())
            ->markupUnit($builder, $this->listService->getUnitArray())
            ->markupMinister($builder, $this->listService->getMinisterArray())
            ->team($builder, $this->listService->getTeamArrayForUnit())
            ->markupTopic($builder, $this->topicService->getTopicsForForm())
            ->status($builder, CaseStatus::getStatusArray())
            ->task($builder, TaskStatus::getTasksForFilterArray());

        $builder->add('search', 'submit', [
            'attr' => [
                'class' => 'button'
            ],
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method'          => 'GET',
            'attr'            => [
                'class'       => 'form-search',
                'data-action' => $this->router->generate('homeoffice_cts_supersearch_search'),
                'novalidate'  => 'novalidate',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
