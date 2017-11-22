<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common\Markup;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NoReplyNeededType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class NoReplyNeededType extends AbstractType
{
    use Groups\MarkupNoReplyNeeded;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->markupNoReplyNeeded($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkupNoReplyNeeded';
    }
}
