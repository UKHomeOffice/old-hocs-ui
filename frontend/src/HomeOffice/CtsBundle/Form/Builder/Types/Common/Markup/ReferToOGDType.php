<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common\Markup;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReferToOGDType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Common
 */
class ReferToOGDType extends AbstractType
{
    use Groups\MarkupReferToOGD;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->markupReferToOGD($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MarkupReferToOGD';
    }
}
