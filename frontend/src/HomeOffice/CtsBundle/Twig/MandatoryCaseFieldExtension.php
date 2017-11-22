<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;

/**
 * Class MandatoryCaseFieldExtension
 * @package HomeOffice\CtsBundle\Twig
 */
class MandatoryCaseFieldExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'mandatory_case_field_extension';
    }

    public function getFunctions()
    {
        return
            array(
                new \Twig_SimpleFunction(
                    'isCaseFieldMandatory',
                    array(
                        $this,
                        'isCaseFieldMandatory'
                    )
                )
            );
    }

    /**
     * @param CtsCase $ctsCase
     * @param string  $fieldName
     * @return bool
     */
    public function isCaseFieldMandatory(CtsCase $ctsCase, $fieldName)
    {

        return (
            in_array($ctsCase->getCaseStatus(), $ctsCase->getCaseMandatoryFieldStatus()) &&
            array_key_exists($fieldName, $ctsCase->getCaseMandatoryFields())
        );
    }
}
