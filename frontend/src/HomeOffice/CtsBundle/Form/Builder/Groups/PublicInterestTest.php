<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PublicInterestTest
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait PublicInterestTest
{
    use Elements\PitExtension;
    use Elements\PitLetterSentDate;
    use Elements\PitQualifiedExemptions;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function publicInterestTest(FormBuilderInterface $builder)
    {
        $this
            ->pitExtension($builder)
            ->pitLetterSentDate($builder)
            ->pitQualifiedExemptions($builder);

        return $this;
    }
}
