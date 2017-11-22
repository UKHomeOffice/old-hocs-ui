<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;

class QueryHelperTest extends ServiceTestHelper
{

    /**
     * @var QueryHelper
     */
    private $instance;
 
    private $caseProperties = array(
            'objectTypeId' => array(
                'object_prop_name' => 'objectTypeId',
                'alfresco_prop_name' => 'cmis:objectTypeId',
                'query_name' => 'c.cmis:objectTypeId',
                'type' => 'id',
                'required_for_dcu' => true,
                'required_for_pq' => true,
                'required_for_case_queue' => true
            ),
            'objectId' => array(
                'object_prop_name' => 'id',
                'alfresco_prop_name' => 'objectId',
                'query_name' => 'c.objectId',
                'type' => 'id',
                'required_for_dcu' => false,
                'required_for_pq' => false,
                'required_for_case_queue' => true
            ),
            'name' => array(
                'object_prop_name' => 'folderName',
                'alfresco_prop_name' => 'name',
                'query_name' => 'c.name',
                'type' => 'string',
                'required_for_dcu' => false,
                'required_for_pq' => false,
                'required_for_case_queue' => false
            ),
            'cts:correspondenceType' => array(
                'object_prop_name' => 'correspondenceType',
                'alfresco_prop_name' => 'cts:correspondenceType',
                'query_name' => 'c.cts:correspondenceType',
                'type' => 'string',
                'required_for_dcu' => true,
                'required_for_pq' => true,
                'required_for_case_queue' => true
            ),
            'cts:dateReceived' => array(
                'object_prop_name' => 'dateReceived',
                'alfresco_prop_name' => 'cts:dateReceived',
                'query_name' => 'c.cts:dateReceived',
                'type' => 'dateTime',
                'required_for_dcu' => true,
                'required_for_pq' => false,
                'required_for_case_queue' => true
            ),
            'cts:priority' => array(
                'object_prop_name' => 'priority',
                'alfresco_prop_name' => 'cts:priority',
                'query_name' => 'c.cts:priority',
                'type' => 'boolean',
                'required_for_dcu' => true,
                'required_for_pq' => false,
                'required_for_case_queue' => true
            ),
            'cts:opDate' => array(
                'object_prop_name' => 'opDate',
                'alfresco_prop_name' => 'cts:opDate',
                'query_name' => 'c.cts:opDate',
                'type' => 'dateTime',
                'required_for_dcu' => false,
                'required_for_pq' => true,
                'required_for_case_queue' => true
            ),
            'cts:opDate' => array(
                'object_prop_name' => 'opDate',
                'alfresco_prop_name' => 'cts:opDate',
                'query_name' => 'c.cts:opDate',
                'type' => 'dateTime',
                'required_for_dcu' => false,
                'required_for_pq' => true,
                'required_for_case_queue' => true
            )
        );
 
    protected function setUp()
    {
        $this->instance = new QueryHelper($this->caseProperties);
    }
 
    public function testConstructSimpleCmisQueryFull()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array("property1 = 'TEST'", 'property3 > 5');
        $orderBy = array('property3', 'property2 DESC');
        $query = $this->instance->constructSimpleCmisQuery($select, $from, $where, $orderBy);
        // @codingStandardsIgnoreStart
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE property1 = 'TEST' AND property3 > 5 ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructSimpleCmisQuerySelectFrom()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $query = $this->instance->constructSimpleCmisQuery($select, $from);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name", $query);
    }
 
    public function testConstructSimpleCmisQuerySelectFromWhere()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array("property1 = 'TEST'", 'property3 > 5');
        $query = $this->instance->constructSimpleCmisQuery($select, $from, $where);
        // @codingStandardsIgnoreStart
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE property1 = 'TEST' AND property3 > 5", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructSimpleCmisQuerySelectFromOrderBy()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array();
        $orderBy = array('property3', 'property2 DESC');
        $query = $this->instance->constructSimpleCmisQuery($select, $from, $where, $orderBy);
        // @codingStandardsIgnoreStart
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithOneFilterWithOneValue()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array("caseStatus='Test'");
        $whereArray = array();
        $whereOrArray = array();
        array_push($whereOrArray, $where);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, array(), $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE (caseStatus='Test' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithOneOrFilterWithMultipleValues()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereArray = array();
        $whereOrArray = array();
        array_push($whereOrArray, $where);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, array(), $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE (caseStatus='Test' OR caseStatus='Testing' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithMultipleOrFiltersWithMultipleValues()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Task2'");
        $whereArray = array();
        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, array(), $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE (caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithInitialWhereAndMultipleOrFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
     
        $whereArray = array("assignedUser='TestUser'");
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Task2'");
        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, array(), $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE assignedUser='TestUser' AND (caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithMultipleInitialWhereAndMultipleOrFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
     
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Task2'");

        $whereArray = array("property1 = 'TEST'", 'property3 > 5');

        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);
     
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, array(), $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE property1 = 'TEST' AND property3 > 5 AND (caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
 
    public function testConstructQueryWithOneAndFilterWithMultipleValues()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $where = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereArray = array();
        $whereAndArray = array();
        array_push($whereAndArray, $where);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, array(), $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE ( caseStatus='Test' AND caseStatus='Testing' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
         
    public function testConstructQueryWithMultipleAndFiltersWithMultipleValues()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereAnd = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereAnd2 = array("caseTask='Task'", "caseTask='Task2'");
        $whereArray = array();
        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, array(), $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE ( caseStatus='Test' AND caseStatus='Testing' ) OR ( caseTask='Task' AND caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
         
    public function testConstructQueryWithInitialWhereAndMultipleAndFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
     
        $whereArray = array("assignedUser='TestUser'");
        $whereAnd = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereAnd2 = array("caseTask='Task'", "caseTask='Task2'");
        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, array(), $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE assignedUser='TestUser' OR ( caseStatus='Test' AND caseStatus='Testing' ) OR ( caseTask='Task' AND caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
         
    public function testConstructQueryWithMultipleInitialWhereAndMultipleAndFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
     
        $whereAnd = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereAnd2 = array("caseTask='Task'", "caseTask='Task2'");

        $whereArray = array("property1 = 'TEST'", 'property3 > 5');

        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);
     
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, array(), $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE property1 = 'TEST' AND property3 > 5 OR ( caseStatus='Test' AND caseStatus='Testing' ) OR ( caseTask='Task' AND caseTask='Task2' ) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
         
    public function testConstructQueryWithOneOrFilterAndOneAndFilter()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereArray = array();
        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        $whereAnd = array("caseStatus='Test2'", "caseStatus='Testing2'");
        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE ((caseStatus='Test' OR caseStatus='Testing' ) OR ( caseStatus='Test2' AND caseStatus='Testing2' )) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }
         
    public function testConstructQueryWithMultipleOrFiltersAndMultipleAndFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Tasking'");

        $whereArray = array();
        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);

        $whereAnd = array("caseStatus='Test2'", "caseStatus='Testing2'");
        $whereAnd2 = array("caseTask='Task2'", "caseTask='Tasking2'");

        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);

        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE ((caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Tasking' ) OR ( caseStatus='Test2' AND caseStatus='Testing2' ) OR ( caseTask='Task2' AND caseTask='Tasking2' )) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }

    public function testConstructQueryWithInitialWhereMultipleOrFiltersAndMultipleAndFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereArray = array("assignedUser='TestUser'");
     
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Tasking'");

        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);

        $whereAnd = array("caseStatus='Test2'", "caseStatus='Testing2'");
        $whereAnd2 = array("caseTask='Task2'", "caseTask='Tasking2'");

        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);

        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE assignedUser='TestUser' AND ((caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Tasking' ) OR ( caseStatus='Test2' AND caseStatus='Testing2' ) OR ( caseTask='Task2' AND caseTask='Tasking2' )) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }

    public function testConstructQueryWithMultipleWhereMultipleOrFiltersAndMultipleAndFilters()
    {
        $select = array('property1', 'property2', 'property3');
        $from = 'type_name';
        $whereArray = array("property1='TEST'", 'property3 > 5');
     
        $whereOr = array("caseStatus='Test'", "caseStatus='Testing'");
        $whereOr2 = array("caseTask='Task'", "caseTask='Tasking'");

        $whereOrArray = array();
        array_push($whereOrArray, $whereOr);
        array_push($whereOrArray, $whereOr2);

        $whereAnd = array("caseStatus='Test2'", "caseStatus='Testing2'");
        $whereAnd2 = array("caseTask='Task2'", "caseTask='Tasking2'");

        $whereAndArray = array();
        array_push($whereAndArray, $whereAnd);
        array_push($whereAndArray, $whereAnd2);

        $orderBy = array('property3', 'property2 DESC');
        // @codingStandardsIgnoreStart
        $query = $this->instance->constructQueryWithMultipleFilters($select, $from, $whereArray, $whereOrArray, $whereAndArray, $orderBy);
        $this->assertEquals("SELECT property1,property2,property3 FROM type_name WHERE property1='TEST' AND property3 > 5 AND ((caseStatus='Test' OR caseStatus='Testing' ) AND (caseTask='Task' OR caseTask='Tasking' ) OR ( caseStatus='Test2' AND caseStatus='Testing2' ) OR ( caseTask='Task2' AND caseTask='Tasking2' )) ORDER BY property3,property2 DESC", $query);
        // @codingStandardsIgnoreEnd
    }

    public function testAddToWhereStatement()
    {
        $where = array();
        $this->instance->addToWhereStatement($where, 'caseStatus', '=', 'Test');
        $this->assertEquals(array("caseStatus='Test'"), $where);
    }

    public function testAddToWhereStatementQuotes()
    {
        $where = array();
        $this->instance->addToWhereStatement($where, 'caseStatus', '=', "It's got quotes'");
        $this->assertEquals(array("caseStatus='It\'s got quotes\''"), $where);
    }

    public function testAddToWhereStatementWildcardStart()
    {
        $where = array();
        $this->instance->addToWhereStatement($where, 'caseStatus', ' LIKE ', "test", true);
        $this->assertEquals(array("caseStatus LIKE '%test'"), $where);
    }

    public function testAddToWhereStatementWildcardEnd()
    {
        $where = array();
        $this->instance->addToWhereStatement($where, 'caseStatus', ' LIKE ', "test", false, true);
        $this->assertEquals(array("caseStatus LIKE 'test%'"), $where);
    }

    public function testAddToWhereStatementWildcardBoth()
    {
        $where = array();
        $this->instance->addToWhereStatement($where, 'caseStatus', ' LIKE ', "test", true, true);
        $this->assertEquals(array("caseStatus LIKE '%test%'"), $where);
    }
 
    public function testAddWhereInToWhereStatement()
    {
        $where = array();
        $values = array('value1', 'value2', 'value3');
        $this->instance->addWhereInToWhereStatement($where, 'caseStatus', $values);
        $this->assertEquals(array("caseStatus IN ('value1', 'value2', 'value3')"), $where);
    }

    public function testAddWhereInToWhereStatementCheckNull()
    {
        $where = array();
        $values = array('value1', 'value2', 'value3');
        $this->instance->addWhereInToWhereStatement($where, 'caseStatus', $values, true);
        $this->assertEquals(array("(caseStatus IS NULL OR caseStatus IN ('value1', 'value2', 'value3'))"), $where);
    }
 
    public function testExtractStandardUrnForQuery()
    {
        $urnSearch = 'MIN/123456/14';
        $result = $this->instance->extractUrnForQuery($urnSearch);
     
        $this->assertEquals($result["urnPrefix"], 'MIN');
        $this->assertEquals($result["urnSuffix"], '123456/14');
    }
 
    public function testExtractUrnWithoutYearForQuery()
    {
        $urnSearch = 'MIN/123456';
        $result = $this->instance->extractUrnForQuery($urnSearch);
     
        $this->assertEquals($result["urnPrefix"], 'MIN');
        $this->assertEquals($result["urnSuffix"], '123456');
    }
 
    public function testExtractUrnWithoutSuffixForQuery()
    {
        $urnSearch = 'MIN';
        $result = $this->instance->extractUrnForQuery($urnSearch);

        $this->assertEquals($result["urnPrefix"], 'MIN');
    }
 
    public function testGetUnitsTeamsForQueryEmptyArray()
    {
        $this->assertEquals('', $this->instance->getUnitsTeamsForQuery(array()));
    }
 
    public function testGetUnitsTeamsForQuerySingleUnit()
    {
        $units = array(
            $this->createUnit('UNIT_NAME', 'Unit name')
        );
        $this->assertEquals("('UNIT_NAME')", $this->instance->getUnitsTeamsForQuery($units));
    }
 
    public function testGetUnitsTeamsForQuerySingleTeam()
    {
        $teams = array(
            $this->createTeam('TEAM_NAME', 'Team name')
        );
        $this->assertEquals("('TEAM_NAME')", $this->instance->getUnitsTeamsForQuery($teams));
    }
 
    public function testGetUnitsTeamsForQueryUnits()
    {
        $units = array(
            $this->createUnit('UNIT_NAME', 'Unit name'),
            $this->createUnit('ANOTHER_UNIT_NAME', 'Another unit name')
        );
        $this->assertEquals("('UNIT_NAME','ANOTHER_UNIT_NAME')", $this->instance->getUnitsTeamsForQuery($units));
    }
 
    public function testGetUnitsTeamsForQueryTeams()
    {
        $teams = array(
            $this->createTeam('TEAM_NAME', 'Team name'),
            $this->createTeam('ANOTHER_TEAM_NAME', 'Another team name')
        );
        $this->assertEquals("('TEAM_NAME','ANOTHER_TEAM_NAME')", $this->instance->getUnitsTeamsForQuery($teams));
    }
 
    public function testOrderByDateColumnHandlerDefaultValues()
    {
        $orderByArray = $this->instance->orderByDateColumnHandler(null, null);
        $this->assertEquals('c.cts:caseResponseDeadline', $orderByArray[0]);
        $this->assertEquals('ASC', $orderByArray[1]);
    }
 
    public function testOrderByDateColumnHandlerDeadlineDescValues()
    {
        $orderByArray = $this->instance->orderByDateColumnHandler('deadlineDate', 'DESC');
        $this->assertEquals('c.cts:caseResponseDeadline', $orderByArray[0]);
        $this->assertEquals('DESC', $orderByArray[1]);
    }
 
    public function testOrderByDateColumnHandlerDeadlineAscValues()
    {
        $orderByArray = $this->instance->orderByDateColumnHandler('deadlineDate', 'ASC');
        $this->assertEquals('c.cts:caseResponseDeadline', $orderByArray[0]);
        $this->assertEquals('ASC', $orderByArray[1]);
    }
 
    public function testOrderByDateColumnHandlerCreatedDescValues()
    {
        $orderByArray = $this->instance->orderByDateColumnHandler('createdDate', 'DESC');
        $this->assertEquals('c.cmis:creationDate', $orderByArray[0]);
        $this->assertEquals('DESC', $orderByArray[1]);
    }
 
    public function testOrderByDateColumnHandlerCreatedAscValues()
    {
        $orderByArray = $this->instance->orderByDateColumnHandler('createdDate', 'ASC');
        $this->assertEquals('c.cmis:creationDate', $orderByArray[0]);
        $this->assertEquals('ASC', $orderByArray[1]);
    }
 
    public function testGetQueryNameArrayFromCaseProperties()
    {
        $selectExpected = array(
            'c.cmis:objectTypeId',
            'c.objectId',
            'c.cts:correspondenceType',
            'c.cts:dateReceived',
            'c.cts:priority',
            'c.cts:opDate'
        );
        $selectActual = $this->instance->getQueryNameArrayFromCaseProperties('required_for_case_queue');
        $this->assertCount(count($selectExpected), $selectActual);
        $this->assertEquals($selectExpected, $selectActual);
    }
}
