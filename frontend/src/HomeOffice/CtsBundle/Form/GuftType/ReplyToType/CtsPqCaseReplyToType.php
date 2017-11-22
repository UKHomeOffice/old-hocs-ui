<?php

namespace HomeOffice\CtsBundle\Form\GuftType\ReplyToType;

use HomeOffice\CtsBundle\Form\GuftType\CtsCaseReplyToType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsPqCaseReplyToType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\ReplyToType
 */
class CtsPqCaseReplyToType extends CtsCaseReplyToType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('constituency', 'text', [
                'required' => false,
                'disabled' => true,
            ])
            ->add('party', 'text', [
                'required' => false,
                'disabled' => true,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsPqCaseReplyTo';
    }
}
