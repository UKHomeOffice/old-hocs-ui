<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FSCI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class FSCI extends BaseFoi
{
    use Elements\IcoComplaintOfficer;
    use Elements\IcoReference;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this
            ->icoComplaintOfficer($builder)
            ->icoReference($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FSCICreate';
    }
}
