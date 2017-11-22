<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToCorrespondent
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function replyToCorrespondent(FormBuilderInterface $builder)
    {
        $case = $builder->getData();

        if (property_exists($case, 'replyToCorrespondent')) {
            $builder->add('replyToCorrespondent', 'checkbox', [
                'label' => 'Reply to',
            ]);
        }

        return $this;
    }
}
