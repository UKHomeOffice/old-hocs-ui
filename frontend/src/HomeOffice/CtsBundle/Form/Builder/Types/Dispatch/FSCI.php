<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FSCI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
class FSCI extends BaseFoi
{
    use Elements\IcoOutcome;
    use Elements\IcoOutcomeDate;
    use Elements\Minute;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this
            ->icoOutcome($builder)
            ->icoOutcomeDate($builder)
            ->minute($builder, 'Comments')
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FSCIDispatch';
    }
}
