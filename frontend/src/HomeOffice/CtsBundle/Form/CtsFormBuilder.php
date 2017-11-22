<?php
/*
 *  Custom FormBuilder to override the Symfony FormBuilder.
 *
 */

namespace HomeOffice\CtsBundle\Form;

use Symfony\Component\Form\FormBuilder;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;

class CtsFormBuilder extends FormBuilder
{
    /**
     * Override FormBuilder Add method to check permissions.
     * @param int|string|\Symfony\Component\Form\FormBuilderInterface $child
     * @param null $type
     * @param array $options
     * @return $this|\Symfony\Component\Form\FormBuilderInterface
     */
    public function add($child, $type = null, array $options = array())
    {

        $ctsCase = $this->getData();
        if ($ctsCase instanceof CtsCase) {
            if (method_exists($ctsCase, 'canEditPropertyPermission')) {
                if (!$ctsCase->canEditPropertyPermission($child)) {
                    $options['disabled'] = true;
                }
            }
        }

        return parent::add($child, $type, $options);
    }
}
