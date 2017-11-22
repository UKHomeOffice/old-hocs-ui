<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderOtherNames
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderPassportNumber
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderPassportNumber(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderPassportNumber', 'text', [
            'attr'     => ['placeholder' => 'Passport number'],
            'label'    => 'Passport number',
            'required' => false,
        ]);

        return $this;
    }
}
