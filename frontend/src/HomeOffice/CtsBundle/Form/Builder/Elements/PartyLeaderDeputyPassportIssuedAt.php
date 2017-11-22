<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderDeputyPassportIssuedAt
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderDeputyPassportIssuedAt
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderDeputyPassportIssuedAt(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderDeputyPassportIssuedAt', 'text', [
            'attr'     => ['placeholder' => 'Passport issued at'],
            'label'    => 'Passport issued at',
            'required' => false,
        ]);

        return $this;
    }
}
