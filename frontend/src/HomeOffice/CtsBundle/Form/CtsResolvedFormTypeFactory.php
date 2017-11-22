<?php
/*
 *  Custom ResolvedFormTypeFactory. Used to launch a custom CTSResolvedFormType
 *
 */

namespace HomeOffice\CtsBundle\Form;

use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;

class CtsResolvedFormTypeFactory extends ResolvedFormTypeFactory
{
    /**
     * Launches a CTS Specific Resolved Form Type
     * {@inheritdoc}
     */
    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        return new CtsResolvedFormType($type, $typeExtensions, $parent);
    }
}
