<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderPassportIssuedOn
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderPassportIssuedOn
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderPassportIssuedOn(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderPassportIssuedOn', 'date', [
            'label'       => 'Passport issued on',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'years'       => range(date('Y') - 10, date('Y') + 1)
        ]);

        return $this;
    }
}
