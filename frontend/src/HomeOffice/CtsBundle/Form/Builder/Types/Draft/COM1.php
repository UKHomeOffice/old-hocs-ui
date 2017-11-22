<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COM1
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class COM1 extends COR
{
    use Elements\HmpoComplaintOutcome;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this->hmpoComplaintOutCome($builder);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'COM1Draft';
    }
}
