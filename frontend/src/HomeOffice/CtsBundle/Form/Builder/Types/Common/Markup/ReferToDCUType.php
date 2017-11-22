<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common\Markup;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReferToDCUType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class ReferToDCUType extends AbstractType
{
    use Groups\MarkupReferToDCU;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->markupReferToDCU($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkupReferToDCU';
    }
}
