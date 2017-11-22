<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderDeputyPassportNumber
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderDeputyPassportNumber
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderDeputyPassportNumber(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderDeputyPassportNumber', 'text', [
            'attr'     => ['placeholder' => 'Passport number'],
            'label'    => 'Passport number',
            'required' => false,
        ]);

        return $this;
    }
}
