<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements\MarkupCloseRefer;
use HomeOffice\CtsBundle\Form\Builder\Elements\OgdName;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupReferToOGD
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupReferToOGD
{
    use OgdName;
    use MarkupCloseRefer;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupReferToOGD(FormBuilderInterface $builder)
    {
        $this
            ->ogdName($builder)
            ->markupCloseRefer($builder);

        return $this;
    }
}
