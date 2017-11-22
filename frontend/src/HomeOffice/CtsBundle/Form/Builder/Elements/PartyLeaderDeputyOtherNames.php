<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderDeputyOtherNames
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderDeputyOtherNames
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderDeputyOtherNames(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderDeputyOtherNames', 'text', [
            'attr'     => ['placeholder' => 'Other names'],
            'label'    => 'Other names',
            'required' => false,
        ]);

        return $this;
    }
}
