<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PartyLeaderLastName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PartyLeaderLastName
{
    /**
     * @param  FormBuilderInterface $builder
     * @return $this
     */
    protected function partyLeaderLastName(FormBuilderInterface $builder)
    {
        $builder->add('partyLeaderLastName', 'text', [
            'attr'     => ['placeholder' => 'Last name'],
            'label'    => 'Last name',
            'required' => false,
        ]);

        return $this;
    }
}
