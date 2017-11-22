<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderDeputyLastName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderDeputyLastName
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderDeputyLastName(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderDeputyLastName', 'text', [
            'attr'     => ['placeholder' => 'Last name'],
            'label'    => 'Last name',
            'required' => false,
        ]);

        return $this;
    }
}
