<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToNumberTenCopy
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToNumberTenCopy
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function replyToNumberTenCopy(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox == true) {
            $builder->add('replyToNumberTenCopy', 'checkbox', [
                'label' => 'Send copy to No. 10',
            ]);
        } else {
            $builder->add('replyToNumberTenCopy', 'choice', [
                'choices'    => [
                    true  => 'Yes',
                    false => 'No',
                    ''    => 'Either',
                ],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Copy to No. 10',
                'label_attr' => ['class' => 'block-label']
            ]);
        }

        return $this;
    }
}
