<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EnforcementNoticeNeeded
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait EnforcementNoticeNeeded
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function enforcementNoticeNeeded(FormBuilderInterface $builder)
    {
        $builder->add('enforcementNoticeNeeded', 'checkbox', [
            'label' => 'Enforcement/Information notice received',
        ]);

        return $this;
    }
}
