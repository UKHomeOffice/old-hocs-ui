<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderDeputyPassportIssuedOn
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderDeputyPassportIssuedOn
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function partyLeaderDeputyPassportIssuedOn(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderDeputyPassportIssuedOn', 'date', [
            'label'       => 'Passport issued on',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'years'       => range(date('Y') - 10, date('Y') + 1)
        ]);

        return $this;
    }
}
