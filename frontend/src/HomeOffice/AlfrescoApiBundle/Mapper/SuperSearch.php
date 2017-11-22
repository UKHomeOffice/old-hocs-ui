<?php

namespace HomeOffice\AlfrescoApiBundle\Mapper;

/**
 * Class SuperSearch
 *
 * @package HomeOffice\AlfrescoApiBundle\Mapper
 */
final class SuperSearch extends AbstractMapper
{
    /**
     * @param  array $response
     * @param  bool  $listHandler
     * @return array
     */
    public function map(array $response, $listHandler = false)
    {
        if (isset($response['results'])) {
            $response['results'] = $this->reduceResults($response['results']);
        }

        return $response;
    }

    /**
     * @param  array $results
     * @param  array $replacement
     * @return array
     */
    private function reduceResults(array $results, $replacement = [])
    {
        foreach ($results as $key => $value) {
            if (isset($value['properties'])) {
                foreach ($value['properties'] as $prop) {
                    $prop = $this->fixEpochTimestamp($prop);
                    $replacement[$key][$prop['localName']] =  $prop['value'];
                }
                $replacement[$key]['nameOrMember'] = $this->nameOrMember(
                    $value['properties'],
                    $this->getType($value['properties'])
                );
                $replacement[$key]['caseHash'] = $this->caseHash($value['properties']);
            }
        }

        return $replacement;
    }

    private function caseHash(array $prop)
    {
        return preg_replace('/workspace\:\/\/SpacesStore\//', '', $prop['c.cmis:objectId']['value']);
    }

    /**
     * Name or Member
     *
     * This is duplicated code from Alfresco.  At this time, we have no way of filtering the CMIS output.
     *
     * @param $prop
     * @param $type
     * @return null|string
     * @see https://github.com/UKHomeOffice/cts-monorepo/blob/master/backend/homeoffice-cts-repo/src/main/java/uk/gov/homeoffice/ctsv2/model/CtsCase.java#L377-L399
     */
    private function nameOrMember($prop, $type)
    {
        switch($type) {
            case in_array($type, ["FOI", "FTC", "FTCI", "FSC", "FSCI", "FLT", "FUT"]):
                return  $this->correspondent($prop);
            case in_array($type, ["UTEN", "NPQ", "LPQ", "OPQ", "IMCB", "IMCM"]):
                return $this->member($prop);
            case in_array($type, ["TRO", "DTEN", "MIN"]):
                return $this->memberOrCorrespondent($prop);
            case in_array($type, ['COM', 'GEN', 'COL']):
                return $this->applicantOrCorrespondent($prop);
            default:
                return '-';
        }
    }

    /**
     * @param  array $properties
     * @return null
     */
    private function getType(array $properties)
    {
        return isset($properties['c.cts:correspondenceType']) ? $properties['c.cts:correspondenceType']['value'] : null;
    }

    /**
     * @param  array $prop
     * @return string
     */
    private function member(array $prop)
    {
        return $prop['c.cts:member']['value'];
    }

    /**
     * @param  array $prop
     * @return null|string
     */
    private function correspondent(array $prop)
    {
        return $prop['c.cts:correspondentForename']['value'] && $prop['c.cts:correspondentSurname']['value']
            ? $prop['c.cts:correspondentForename']['value'] . ' ' . $prop['c.cts:correspondentSurname']['value']
            : null;
    }

    /**
     * @param  array $prop
     * @return null|string
     */
    private function memberOrCorrespondent(array $prop)
    {
        $member = $this->member($prop);
        return $member ? $member : $this->correspondent($prop);
    }

    /**
     * @param  array $prop
     * @return null|string
     */
    private function applicantOrCorrespondent(array $prop)
    {
        return $prop['c.cts:applicantForename']['value'] && $prop['c.cts:applicantSurname']['value']
            ? $prop['c.cts:applicantForename']['value'] . ' ' . $prop['c.cts:applicantSurname']['value']
            : $this->correspondent($prop);
    }

    /**
     * @param  array $prop
     * @return array
     */
    private function fixEpochTimestamp(array $prop)
    {
        if ($prop['type'] === 'datetime' && $prop['value'] !== null) {
            $prop['value'] = substr($prop['value'], 0, strlen($prop['value']) -3 );
        }

        return $prop;
    }
}
