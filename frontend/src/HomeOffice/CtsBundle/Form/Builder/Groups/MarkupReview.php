<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements\ReviewedByPermSec;
use HomeOffice\CtsBundle\Form\Builder\Elements\ReviewedBySpads;
use HomeOffice\CtsBundle\Form\Builder\Elements\SignedByHomeSec;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupReview
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupReview
{
    use ReviewedByPermSec;
    use ReviewedBySpads;
    use SignedByHomeSec;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function markupReview(FormBuilderInterface $builder)
    {
        $this
            ->reviewedByPermSec($builder)
            ->reviewedBySpads($builder)
            ->signedByHomeSec($builder);
        ;

        return $this;
    }
}

