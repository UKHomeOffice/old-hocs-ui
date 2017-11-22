<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderPassportIssuedOn
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderPassportIssuedAt
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderPassportIssuedAt(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderPassportIssuedAt', 'text', [
            'attr'     => ['placeholder' => 'Passport issued at'],
            'label'    => 'Passport issued at',
            'required' => false,
        ]);

        return $this;
    }
}
