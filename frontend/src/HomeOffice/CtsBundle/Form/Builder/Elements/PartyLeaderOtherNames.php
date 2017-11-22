<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderOtherNames
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderOtherNames
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderOtherNames(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderOtherNames', 'text', [
            'attr'     => ['placeholder' => 'Other names'],
            'label'    => 'Other names',
            'required' => false,
        ]);

        return $this;
    }
}
