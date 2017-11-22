<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Common;

use HomeOffice\CtsBundle\Form\Builder\Elements\AddAppeals;
use HomeOffice\CtsBundle\Form\Builder\Elements\AppealsToLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LinkedAppeals
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
class Appeals extends AbstractType
{
    use AddAppeals, AppealsToLink;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAppeals($builder);
        $this->appealsToLink($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Appeals';
    }
}
