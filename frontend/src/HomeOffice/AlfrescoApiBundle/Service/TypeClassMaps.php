<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCollCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCorCase;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Session\Session;

class TypeClassMaps
{
    /**
     *
     * @var array
     */
    private static $supertypeMap = [
        "MIN"  => [ 'DCU',  'Ministerial' ],
        "TRO"  => [ 'DCU',  'Treat Official' ],
        "DTEN" => [ 'DCU',  'DCU No. 10' ],
        "FOI"  => [ 'FOI',  'FOI' ],
        'FTC'  => [ 'FOI',  'FOI Internal Review: Time Complaint' ],
        'FTCI' => [ 'FOI',  'FOI ICO Time Complaint' ],
        'FSC'  => [ 'FOI',  'FOI Internal Review: Substantive Complaint' ],
        'FSCI' => [ 'FOI',  'FOI ICO Substantive Complaint' ],
        'FLT'  => [ 'FOI',  'FOI Lower Tribunal' ],
        'FUT'  => [ 'FOI',  'FOI Upper Tribunal' ],
        "IMCB" => [ 'UKVI', 'UKVI B Ref' ],
        "IMCM" => [ 'UKVI', 'UKVI M Ref' ],
        "UTEN" => [ 'UKVI', 'UKVI No. 10' ],
        "COM"  => [ 'HMPO', 'HMPO Complaint' ],
        "GEN"  => [ 'HMPO', 'HMPO General' ],
        "COL"  => [ 'HMPO', 'HMPO Collective' ]
    ];
 
    /**
     *
     * @var array
     */
    private static $searchSupertypeMap = [
        'MIN'  => 'MIN',
        'TRO'  => 'TRO',
        'DTEN' => 'TEN',
        'UTEN' => 'TEN',
        'FOI'  => 'FOI',
        'FTC'  => 'FOIC',
        'FTCI' => 'FOIC',
        'FSC'  => 'FOIC',
        'FSCI' => 'FOIC',
        'FLT'  => 'FOIC',
        'FUT'  => 'FOIC',
        'IMCB' => 'UKVI',
        'IMCM' => 'UKVI',
        'COM'  => 'COM',
        'GEN'  => 'GEN',
        'COL'  => 'COL'
    ];

    /**
     *
     * @var array
     * indexes
     * 0: Case class
     * 1: Form class
     * 2: boolean indicating whether it has a correspondent stoplist
     */
    private static $subtypeClassMap = [
        'MIN'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsDcuMinisterialCaseType',
            true
        ],
        'TRO'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsDcuTreatOfficialCaseType',
            false
        ],
        'DTEN' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case',
            '\HomeOffice\CtsBundle\Form\%s\CtsNo10CaseType',
            true
        ],
        'FOI'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiCaseType',
            false
        ],
        'FTC'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'FTCI' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'FSC'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'FSCI' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'FLT'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'FUT'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiComplaintCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsFoiComplaintCaseType',
            false
        ],
        'IMCB' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsUkviCaseType',
            true
        ],
        'IMCM' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsUkviCaseType',
            true
        ],
        'UTEN' => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case',
            '\HomeOffice\CtsBundle\Form\%s\CtsNo10CaseType',
            true
        ],
        'COM'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'GEN'  => [
            '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase',
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoGenCaseType',
            false
        ],
        'COL'  => [
            CtsHmpoCollCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'COR'  => [
            CtsHmpoCorCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'COM1'  => [
            CtsHmpoCorCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'COM2'  => [
            CtsHmpoCorCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'DGEN'  => [
            CtsHmpoCorCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
        'GNR'  => [
            CtsHmpoCorCase::class,
            '\HomeOffice\CtsBundle\Form\%s\CtsHmpoComCaseType',
            false
        ],
    ];

    /**
     *
     * @param string $subtype
     * @return string
     */
    public static function getEntityClass($subtype)
    {
        return array_key_exists($subtype, self::$subtypeClassMap) ?
            self::$subtypeClassMap[$subtype][0] :
            null;
    }

    /**
     *
     * @param string $subtype
     * @return string
     */
    public static function getFormClass($subtype)
    {
        $featureToggle = new CtsFeaturesToggle(new Session);

        return
            array_key_exists($subtype, self::$subtypeClassMap) ?
            sprintf(self::$subtypeClassMap[$subtype][1], $featureToggle->get() ? 'GuftType' : 'Type') :
            null;
    }

    /**
     * @param $subtype
     * @return bool
     */
    public static function hasCorrespondentStoplist($subtype)
    {
        return
            array_key_exists($subtype, self::$subtypeClassMap) ?
                self::$subtypeClassMap[$subtype][2] :
                null;
    }

    /**
     *
     * @param string $subtype
     * @return string
     */
    public static function getSupertype($subtype)
    {
        return
            array_key_exists($subtype, self::$supertypeMap) ?
            self::$supertypeMap[$subtype][0] :
            null;
    }

    /**
     *
     * @param string $subtype
     * @return string
     */
    public static function getSearchSupertype($subtype)
    {
        return
            array_key_exists($subtype, self::$searchSupertypeMap) ?
            self::$searchSupertypeMap[$subtype] :
            null;
    }

    /**
     *
     * @param string $subtype
     * @return string
     */
    public static function getName($subtype)
    {
        return
            array_key_exists($subtype, self::$supertypeMap) ?
            self::$supertypeMap[$subtype][1] :
            null;
    }
}
