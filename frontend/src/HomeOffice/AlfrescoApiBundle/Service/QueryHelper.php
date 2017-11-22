<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

class QueryHelper
{
    /**
     *
     * @var array
     */
    private $ctsCaseProperties;
 
    // @codingStandardsIgnoreStart
    /**
     *
     * @var string
     */
    private static $QUEUE_FROM_STATEMENT = 'cts:case as c JOIN cts:groupedMaster as gm ON gm.cmis:objectId = c.cmis:objectId JOIN cts:groupedSlave as gs ON gs.cmis:objectId = c.cmis:objectId JOIN cts:linkedCase as lc ON lc.cmis:objectId = c.cmis:objectId';

    /**
     *
     * @var string
     */
    private static $SEARCH_FROM_STATEMENT = 'cts:case as c JOIN cts:groupedMaster as gm ON gm.cmis:objectId = c.cmis:objectId JOIN cts:groupedSlave as gs ON gs.cmis:objectId = c.cmis:objectId JOIN cts:linkedCase as lc ON lc.cmis:objectId = c.cmis:objectId';
    // @codingStandardsIgnoreEnd

    const CTS_CASE_TYPE = 'cts:case';
 
    /**
     *
     * @param array $ctsCaseProperties
     */
    public function __construct($ctsCaseProperties)
    {
        $this->ctsCaseProperties = $ctsCaseProperties;
    }

    /**
     * @param string $requiredType
     *
     * @return array
     */
    public function getQueryNameArrayFromCaseProperties($requiredType)
    {
        $select = [];
        foreach ($this->ctsCaseProperties as $property) {
            if (array_key_exists($requiredType, $property) && $property[$requiredType]) {
                array_push($select, $property['query_name']);
            }
        }

        return $select;
    }
 
    /**
     *
     * @return array
     */
    public function getQueueSelectStatement()
    {
        return $this->getQueryNameArrayFromCaseProperties('required_for_case_queue');
    }
 
    /**
     *
     * @return string
     */
    public static function getQueueFromStatement()
    {
        return self::$QUEUE_FROM_STATEMENT;
    }

    /**
     *
     * @return array
     */
    public function getQueueOrderByStatement($orderByField, $orderByDirection)
    {
        return array($orderByField.' '.$orderByDirection);
    }
 
    /**
     *
     * @return array
     */
    public function getSearchSelectStatement()
    {
        return $this->getQueryNameArrayFromCaseProperties('required_for_search_results');
    }
 
    /**
     *
     * @return string
     */
    public static function getSearchFromStatement()
    {
        return self::$SEARCH_FROM_STATEMENT;
    }

    /**
     *
     * @return array
     */
    public function getSearchOrderByStatement($orderByField, $orderByDirection)
    {
        return array($orderByField.' '.$orderByDirection);
    }

    /**
     * Return a constructed CMIS query.
     * Supply array of properties to select, array of where clauses to be AND'd and
     * array of properties to order by.
     * @param array $select
     * @param string $from
     * @param array $where
     * @param array $orderBy
     * @return string
     */
    public function constructSimpleCmisQuery($select, $from, $where = array(), $orderBy = array())
    {
        $query = 'SELECT ' . implode(',', $select);
        $query .= ' FROM ' . $from;
        $query .= (count($where) != 0) ? ' WHERE ' . implode(' AND ', $where) : '';
        $query .= (count($orderBy) != 0) ? ' ORDER BY ' . implode(',', $orderBy) : '';
        return $query;
    }

    /**
     * Return a constructed CMIS query.
     *
     * @param array $select
     * @param string $from
     * @param array $where - array of clauses to be And'd
     * @param array $whereOr - 2D array, with relationship between each array AND'd
     * and each property within an array to be OR'd (e.g. used for combining filters)
     * @param array $whereAnd - 2D array, with relationship between each array OR'd
     * and each property within an array to be AND'd (e.g. used for combining a forename and surname)
     * @param array $orderBy
     * @return string
     */
    public function constructQueryWithMultipleFilters(
        $select,
        $from,
        $where = array(),
        $whereOr = array(),
        $whereAnd = array(),
        $orderBy = array()
    ) {
        $i = 0;
     
        //This is to put brackets round the whereOr and whereAnd statements if they both exist
        $groupWhereOpen = "";
        $isGroupWhereOpen = false;
        if (count($whereOr) > 0 && count($whereAnd) > 0) {
            $groupWhereOpen = "(";
            $isGroupWhereOpen = true;
        }
             
        $query = 'SELECT ' . implode(',', $select);
        $query .= ' FROM ' . $from;
        $query .= (count($where) != 0) ? ' WHERE ' . implode(' AND ', $where) : '';
        if (count($where) != 0) {
            //Ensure where statement is not added twice
            $i++;
        }
        foreach ($whereOr as $value) {
            if ($i == 0) {
                $query .= (count($value) != 0) ? ' WHERE ('. $groupWhereOpen . implode(' OR ', $value) . ' )' : '';
                if (count($value) != 0) {
                    //Go to else after first entry added to where clause
                    $i++;
                }
            } else {
                $query .= (count($value) != 0) ? ' AND ('. $groupWhereOpen . implode(' OR ', $value) : '';
                $query .= (count($value) != 0) ? ' )' : '';
            }
            $groupWhereOpen = "";
        }
        foreach ($whereAnd as $value) {
            if ($i == 0) {
                $query .= (count($value) != 0) ? ' WHERE ( ' . implode(' AND ', $value) . ' )' : '';
                if (count($value) != 0) {
                    //Go to else after first entry added to where clause
                    $i++;
                }
            } else {
                $query .= (count($value) != 0) ? ' OR ( ' . implode(' AND ', $value) : '';
                $query .= (count($value) != 0) ? ' )' : '';
            }
        }
        if ($isGroupWhereOpen) {
            $query .= ')';
        }
        $query .= (count($orderBy) != 0) ? ' ORDER BY ' . implode(',', $orderBy) : '';
        return $query;
    }
 
    /**
     * Method to allow multiple values to be added to a where statement
     *
     * @param array $whereArray Current where array passed by reference (&)
     * @param string $whereKey
     * @param string $whereSymbol
     * @param string $whereValue
     * @param boolean $addWildcardStart
     * @param boolean $addWildcardEnd
     *
     * @return $this
     */
    public function addToWhereStatement(
        & $whereArray,
        $whereKey,
        $whereSymbol,
        $whereValue,
        $addWildcardStart = false,
        $addWildcardEnd = false
    ) {
        if ($whereValue == '' || $whereValue == 'ANY_ALLOWED') {
            return $this;
        }
        //@codingStandardsIgnoreStart
        $processedWhereValue = ($addWildcardStart ? '%' : '') . str_replace('\"', '"', addslashes($whereValue)) . ($addWildcardEnd ? '%' : '');
        //@codingStandardsIgnoreEnd
        array_push($whereArray, $whereKey . $whereSymbol . "'$processedWhereValue'");

        return $this;
    }
 
    /**
     *
     * @param array $whereArray Current where array passed by reference (&)
     * @param string $whereKey
     * @param array $valueArray
     * @param boolean $checkNull
     */
    public function addWhereInToWhereStatement(& $whereArray, $whereKey, $valueArray, $checkNull = false)
    {
        if (count($valueArray) > 0) {
            foreach ($valueArray as $key => $value) {
                $valueArray[$key] = addslashes($value);
            }
            $condition = "";
            if ($checkNull) {
                $condition = "($whereKey IS NULL OR ";
            }

            $condition .= $whereKey . " IN (" . "'" . implode("', '", $valueArray) . "'" . ")";

            if ($checkNull) {
                $condition .= ")";
            }

            array_push($whereArray, $condition);
        }
    }
 
    /**
     * Helper to add a where clause for a Yes/No boolean search field.
     * @param string $searchValue
     * @param string $queryField
     * @param array $whereArray
     */
    public function generateWhereForYesNoBoolean($searchValue, $queryField, & $whereArray)
    {
        if ($searchValue != 'ANY_ALLOWED') {
            if ($searchValue == 'Yes') {
                $this->addToWhereStatement($whereArray, $queryField, ' = ', 'true');
            } else {
                $this->addToWhereStatement($whereArray, $queryField, ' = ', 'false');
            }
        }
    }
 
    /**
     * Method to extract a urn string. It will put the result into an array, the first part
     * for the urn prefix, the second for the urn suffix
     * @param string $urnSearch
     * @return array
     */
    public function extractUrnForQuery($urnSearch)
    {
        preg_match('/^[A-Za-z]+/', $urnSearch, $matches);
        if (count($matches) > 0) {
            $caseType = $matches[0];
        } else {
            $caseType = '';
        }
        preg_match('/[0-9\/]+$/', $urnSearch, $matches);
        if (count($matches) > 0) {
            $suffix = preg_replace('/^[\/]/', '', $matches[0]);
        } else {
            $suffix = '';
        }
        return array('urnPrefix' => $caseType, 'urnSuffix' => $suffix);
    }
 
    /**
     *
     * @param array $unitTeamObjectArray
     * @return string
     */
    public function getUnitsTeamsForQuery($unitTeamObjectArray)
    {
        if (empty($unitTeamObjectArray)) {
            return '';
        }
        $unitTeamStringArray = array();
        foreach ($unitTeamObjectArray as $unit) {
            array_push($unitTeamStringArray, "'".addslashes($unit->getAuthorityName())."'");
        }
        return '('.implode(',', $unitTeamStringArray).')';
    }
 
    /**
     *
     * @param array $unitObjectArray
     * @return array
     */
    public function getCaseTypesForQuery($unitObjectArray)
    {
        if (empty($unitObjectArray)) {
            return '';
        }
        $caseTypeStringArray = array();
        foreach ($unitObjectArray as $unit) {
            array_push($caseTypeStringArray, $unit->getAuthorityName());
        }
        return $caseTypeStringArray;
    }

    /**
     * @param array $requestQuery
     * @return array
     */
    public function orderByDateColumnHandler($orderByValue, $orderDirectionValue)
    {
        $orderByField = 'c.cts:caseResponseDeadline';
        $orderByDirection = 'ASC';
        if ($orderByValue != null && $orderByValue == 'deadlineDate') {
            $orderByDirection = $orderDirectionValue;
        } elseif ($orderByValue != null && $orderByValue == 'createdDate') {
            $orderByField = 'c.cmis:creationDate';
            $orderByDirection = $orderDirectionValue;
        }
        return array($orderByField, $orderByDirection);
    }
}
