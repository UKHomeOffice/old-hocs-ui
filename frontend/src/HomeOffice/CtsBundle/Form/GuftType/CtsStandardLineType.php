<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine;
use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class CtsStandardLineType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsStandardLineType extends AbstractType
{
    use Elements\StandardLineName;
    use Elements\ReviewDate;
    use Elements\AssociatedUnit;
    use Elements\AssociatedTopic;
    use Elements\File;
    use Elements\NewFile;
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
        /** @var CtsCaseStandardLine $standardLine */
        $standardLine = $builder->getData();

        $topics = $standardLine->isNew() ? [] : $this->topicService->getTopicsForForm(
            null,
            $standardLine->getAssociatedUnit()
        );

        $this
            ->standardLineName($builder)
            ->reviewDate($builder)
            ->associatedUnit($builder, $this->listService->getUnitArray())
            ->associatedTopic($builder, $topics)
            ->file($builder)
            ->save($builder)
        ;

        if ($standardLine->isNew() === false) {
            $this->newFile($builder);
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
            $form = $event->getForm();
            $data = $event->getData();

            if (!is_null($data)) {
                $form->add('associatedTopic', 'choice', [
                    'choices' => $this->topicService->getTopicsForForm(null, $data['associatedUnit']),
                ]);
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CtsStandardLine';
    }
}
