<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class HmpoPersonTypes
{
    const APPLICANT = 'Applicant';
    const COMPLAINANT = 'Complainant';
    const REP_OF_COMPLAINANT = 'Rep. of complainant';
    const OTHER = 'Other';
    const THIRD_PARTY = 'Third party';
 
    const FAMILY_RELATION = 'Family relation';
    const FRIEND = 'Friend';
    const LEGAL_REPRESENTATIVE = 'Legal representative';
    const MP = 'MP';
 
    /**
     * Returns an array of all the constants used for correspondent type.
     * @return array
     */
    public static function getHmpoComCorrespondentTypeArray()
    {
        $correspondentTypeArray = array();
        $correspondentTypeArray[self::APPLICANT] = self::APPLICANT;
        $correspondentTypeArray[self::COMPLAINANT] = self::COMPLAINANT;
        $correspondentTypeArray[self::REP_OF_COMPLAINANT] = self::REP_OF_COMPLAINANT;
        $correspondentTypeArray[self::OTHER] = self::OTHER;
        return $correspondentTypeArray;
    }
 
    /**
     * Returns an array of all the constants used for correspondent type.
     * @return array
     */
    public static function getHmpoGenCorrespondentTypeArray()
    {
        $correspondentTypeArray = array();
        $correspondentTypeArray[self::APPLICANT] = self::APPLICANT;
        $correspondentTypeArray[self::THIRD_PARTY] = self::THIRD_PARTY;
        return $correspondentTypeArray;
    }

    /**
     * Returns an array of all the constants used for complainant type.
     * @return array
     */
    public static function getHmpoComComplainantTypeArray()
    {
        $complainantTypeArray = array();
        $complainantTypeArray[self::FAMILY_RELATION] = self::FAMILY_RELATION;
        $complainantTypeArray[self::FRIEND] = self::FRIEND;
        $complainantTypeArray[self::LEGAL_REPRESENTATIVE] = self::LEGAL_REPRESENTATIVE;
        $complainantTypeArray[self::MP] = self::MP;
        $complainantTypeArray[self::OTHER] = self::OTHER;
        return $complainantTypeArray;
    }
 
    /**
     * Returns an array of all the constants used for representative type.
     * @return array
     */
    public static function getHmpoComRepresentativeTypeArray()
    {
        $representativeTypeArray = array();
        $representativeTypeArray[self::FAMILY_RELATION] = self::FAMILY_RELATION;
        $representativeTypeArray[self::FRIEND] = self::FRIEND;
        $representativeTypeArray[self::LEGAL_REPRESENTATIVE] = self::LEGAL_REPRESENTATIVE;
        $representativeTypeArray[self::MP] = self::MP;
        $representativeTypeArray[self::OTHER] = self::OTHER;
        return $representativeTypeArray;
    }
 
    public static function getHmpoGenThirdPartyTypeArray()
    {
        $thirdPartyTypeArray = array();
        $thirdPartyTypeArray[self::FAMILY_RELATION] = self::FAMILY_RELATION;
        $thirdPartyTypeArray[self::FRIEND] = self::FRIEND;
        $thirdPartyTypeArray[self::LEGAL_REPRESENTATIVE] = self::LEGAL_REPRESENTATIVE;
        $thirdPartyTypeArray[self::MP] = self::MP;
        $thirdPartyTypeArray[self::OTHER] = self::OTHER;
        return $thirdPartyTypeArray;
    }
}
