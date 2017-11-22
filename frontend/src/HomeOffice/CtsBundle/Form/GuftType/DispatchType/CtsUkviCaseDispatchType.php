<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DispatchType;

use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsUkviCaseDispatchType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\DispatchType
 */
class CtsUkviCaseDispatchType extends GuftFormType
{
    use CtsCaseTransitions;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsUkviCaseDispatch';
    }

}
