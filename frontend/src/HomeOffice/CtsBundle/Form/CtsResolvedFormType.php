<?php
/*
 *  Custom FormBuilder to override the Symfony FormBuilder.
 *
 */

namespace HomeOffice\CtsBundle\Form;

use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CtsResolvedFormType extends ResolvedFormType
{

    /**
     * Override to newBuilder to return custom CtsFormBuilder
     * {@inheritdoc}
     */
    protected function newBuilder($name, $dataClass, FormFactoryInterface $factory, array $options)
    {

        //Skipped checks for button/submit button as CTSFormBuilder is used only for forms.

        return new CtsFormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
    }
}
