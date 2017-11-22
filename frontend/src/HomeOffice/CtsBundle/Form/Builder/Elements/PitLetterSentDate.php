<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PitLetterSentDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PitLetterSentDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function pitLetterSentDate(FormBuilderInterface $builder)
    {
        $builder->add('pitLetterSentDate', 'date', [
            'label'       => 'When did you send the letter?',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly']
        ]);

        return $this;
    }
}
