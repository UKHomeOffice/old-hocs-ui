<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;

/**
 * Class CaseCorrespondentTypeExtension
 *
 * @package HomeOffice\CtsBundle\Twig
 */
class CaseCorrespondentTypeExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $subTypes;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'correspondentSubType' => new \Twig_Function_Method($this, 'getSubType'),
            'caseType' => new \Twig_Function_Method($this, 'getCaseType'),
        ];
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function getSubType($type)
    {
        if (is_null($this->subTypes)) {
            $this->subTypes = CaseCorrespondenceSubType::getCorrespondenceSubTypeArray();
        }

        return array_key_exists($type, $this->subTypes) ? $this->subTypes[$type] : null;
    }

    /**
     * @param string $correspondenceType
     *
     * @return string|null
     */
    public function getCaseType($correspondenceType)
    {
        return CaseCorrespondenceSubType::getCaseType($correspondenceType);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'case_correspondent_type_extension';
    }
}
