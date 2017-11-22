<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupReferToDCU
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupReferToDCU
{
    use Elements\OgdNameFixedDcu;
    use Elements\ReferComment;
    use Elements\MarkupUnit;
    use Elements\MarkupCloseRefer;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupReferToDCU(FormBuilderInterface $builder)
    {
        $this
            ->ogdNameFixedDcu($builder)
            ->referComment($builder)
            ->markupCloseRefer($builder)
        ;

        return $this;
    }
}
