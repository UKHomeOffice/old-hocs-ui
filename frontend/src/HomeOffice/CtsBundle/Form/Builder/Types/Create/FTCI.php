<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FTCI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class FTCI extends BaseFoi
{
    use Elements\IcoComplaintOfficer;
    use Elements\IcoReference;
    use Elements\EnforcementNoticeDeadline;
    use Elements\EnforcementNoticeNeeded;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this
            ->icoComplaintOfficer($builder)
            ->icoReference($builder)
            ->enforcementNoticeNeeded($builder)
            ->enforcementNoticeDeadline($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FTCICreate';
    }
}
