<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COR
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
abstract class COR extends AbstractApproveType
{
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\Refund;
    use Elements\HmpoComplaintOutcome;
    use Elements\ApproveOnBehalf;
    use Elements\ColleagueName;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->refund($builder)
            ->approveOnBehalf($builder)
            ->colleagueName($builder)
        ;
    }
}
