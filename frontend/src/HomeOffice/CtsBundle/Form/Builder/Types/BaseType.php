<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCollCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\CtsBundle\Form\Builder\Groups\Transitions;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BaseType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types
 */
abstract class BaseType extends AbstractType implements ContainerAwareInterface
{
    use Transitions;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Set Container
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ListService
     */
    protected function getListService()
    {
        /** @var ListService $listService */
        $listService = $this->container->get('home_office.list_service');

        return $listService;
    }

    /**
     * @return TopicService
     */
    protected function getTopicsService()
    {
        return $this->container->get('home_office_alfresco_api.service.topic');
    }

    /**
     * @param FormInterface $form
     *
     * @return SubmitButton|null
     */
    protected function getClickedButton(FormInterface $form)
    {
        return $form->getRoot()->getClickedButton();
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function isEditable(CtsCase $case)
    {
        if ($case instanceof CtsHmpoCollCase) {
            return true;
        }

        if ($case instanceof CtsPqCase) {
            return !in_array($case->getCaseStatus(), [CaseProgressHelper::PROGRESS_COMPLETED]);
        }

        if ($case instanceof CtsFoiComplaintCase &&
            $this->getStage() == CaseProgressHelper::PROGRESS_DISPATCH &&
            $case->getCaseStatus() == CaseProgressHelper::PROGRESS_HOLD
        ) {
            return true;
        }

        return in_array($case->getCaseStatus(), [
            CaseProgressHelper::PROGRESS_CREATE,
            CaseProgressHelper::PROGRESS_DRAFT
        ]);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function isLocked(CtsCase $case)
    {
        return $case->getCaseStatus() === CaseProgressHelper::PROGRESS_COMPLETED;
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function isDisabled(CtsCase $case)
    {
        return ! in_array($case->getCaseStatus(), ['New', 'Draft']);
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function applyReadOnly(FormBuilderInterface $builder)
    {
        if ($builder->getData()->getCaseStatus() !== $this->getStage()) {
            /** @var FormBuilderInterface $field */
            foreach ($builder->all() as $key => $field) {
                if ($field->getType()->getOptionsResolver()->isKnown('read_only')) {
                    $options = array_merge(
                        $field->getOptions(),
                        ['read_only' => !$this->isEditable($builder->getData())]
                    );

                    $builder->remove($key);
                    $builder->add($key, $field->getType()->getName(), $options);
                }
            }
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildElements($builder, $options);
        $this->transitions($builder);

        $this->applyReadOnly($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => CtsCase::class,
            'cascade_validation' => true,
            'method'             => 'POST',
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    abstract protected function buildElements(FormBuilderInterface $builder, array $options);

    /**
     * @return string
     */
    abstract public function getStage();
}
