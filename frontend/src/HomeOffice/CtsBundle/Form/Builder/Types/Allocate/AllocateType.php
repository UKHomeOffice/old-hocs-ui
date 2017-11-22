<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Allocate;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowTransition;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use HomeOffice\ListBundle\DependencyInjection\ListServiceAwareInterface;
use HomeOffice\ListBundle\DependencyInjection\ListServiceAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AllocateType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Allocate
 */
class AllocateType extends AbstractType implements ListServiceAwareInterface
{
    use Groups\Allocate;
    use ListServiceAwareTrait;

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->allocate($builder, $this->listService);

        $builder->remove('allocateTo');
        $builder->add('allocateTo', 'hidden', [
            'data' => 0,
            'mapped' => false,
        ]);

        $builder->add('ManualAllocate', 'submit', [
            'label' => 'Allocate',
            'attr'  => ['class' => 'button'],
        ]);

        $builder->get('ManualAllocate')->setAttribute('transition', new CtsCaseWorkflowTransition(
            'Allocate',
            'Allocate',
            true,
            'Allocate',
            'Green'
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Allocate';
    }
}
