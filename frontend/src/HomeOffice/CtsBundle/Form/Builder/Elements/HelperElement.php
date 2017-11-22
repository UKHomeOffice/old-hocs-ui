<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HelperElement
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
class HelperElement
{
    /**
     * @param $helper
     *
     * @return array
     */
    public static function getHelperConstants($helper)
    {
        $types = (new \ReflectionClass($helper))->getConstants();

        return array_combine($types, $types);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string               $group
     * @param string               $groupName
     */
    public static function addCommonType(FormBuilderInterface $builder, $group, $groupName)
    {
        $builder->add($group, $groupName, [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
