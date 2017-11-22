<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupNoReplyNeeded
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupNoReplyNeeded
{
    use Elements\MarkupCancelDetails;
    use Elements\MarkupCloseCancel;

    /**
     * @inheritdoc
     *
     * @return static
     */
    public function markupNoReplyNeeded(FormBuilderInterface $builder)
    {
        $this
            ->markupCancelDetails($builder)
            ->markupCloseCancel($builder)
        ;

        return $this;
    }
}
