<?php namespace HomeOffice\ListBundle\Tests\Service;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\ListBundle\Service\ListHandler;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\ListBundle\Service\CSVParser;
use HomeOffice\AlfrescoApiBundle\Entity\Member;
use Tedivm\StashBundle\Service\CacheService;

class ListHandlerTest extends WebTestCase
{
 
    /**
     * @var CtsListHandler
     */
    private $ctsListHandler;
 
    /**
     *
     * @var string
     */
    //@codingStandardsIgnoreStart
    private $correspondentList = "\rTest member,Test member display,labour,london,1234,True,False,False,True\rFred Bloggs,Fred Bloggs display,green,manchester,4567,False,True,True,False";
    //@codingStandardsIgnoreEnd
    /**
     *
     * @var string
     */
    private $topicList = '{
        "listName": "topicList",
        "listItems": [
            {
                "topicText": "Cats",
                "pqStopList": true,
                "topicUnit": "GROUP_DCU"
            },
            {
                "topicText": "Dogs",
                "pqStopList": false,
                "topicUnit": "GROUP_Parliamentary Questions Team"
            }
        ]
    }';
 
    /**
     *
     * @var string
     */
    private $ministerList = "\rMinister 1,GROUP 1\rMinister 2,GROUP 2";
 
    /**
     *
     * @var array
     */
    private $testMember = ['Test member', 'Test member display', 'labour', 'london', '1234', 'True', 'False'];
 
    /**
     *
     * @var array
     */
    private $fredMember = ['Fred Bloggs', 'Fred Bloggs display', 'green', 'manchester', '4567', 'False', 'True'];
 
    /**
     *
     * @var array
     */
    private $listDefinitions = array(
        'ctsTopicList' =>
            array(
                'alfresco_list_name' => 'topicList',
                'type' => 'dataList',
                'storage' => 'session',
                'prepare_method_name' => 'prepareTopicList',
                ),
            'pqTopicStopList' =>
            array (
                'alfresco_list_name' => 'topicList',
                'type' => 'dataList',
                'storage' => 'session',
                'prepare_method_name' => 'preparePqTopicStopList',
                ),
            'pqCorrespondentStopList' =>
            array(
                'file_name' => 'CorrespondentStopList.csv',
                'type' => 'csv',
                'storage' => 'session',
                'prepare_method_name' => 'preparePqCorrespondentStopList',
                ),
            'ukviCorrespondentStopList' =>
            array(
              'file_name' => 'CorrespondentStopList.csv',
              'storage' => 'session',
              'prepare_method_name' => 'prepareUkviCorrespondentStopList',
                ),
            'dcuCorrespondentStopList' =>
            array(
                'file_name' => 'CorrespondentStopList.csv',
                'type' => 'csv',
                'storage' => 'session',
                'prepare_method_name' => 'prepareDcuCorrespondentStopList',
                ),
            'ctsMemberList' =>
            array(
                'file_name' => 'CorrespondentStopList.csv',
                'type' => 'csv',
                'storage' => 'session',
                'prepare_method_name' => 'prepareMemberList',
                ),
            'ctsMinisterList' =>
            array(
                'file_name' => 'MinisterList.csv',
                'type' => 'csv',
                'storage' => 'cache',
                'prepare_method_name' => 'prepareMinisterList',
                ),
            'ctsUnitAndTeamList' =>
            array(
                'type' => 'custom',
                'storage' => 'session',
                'retrieve_method_name' => 'getUnitsAndTeams',
                )
        );
 
    private function setListHander($listRepo)
    {
        $this->ctsListHandler = new ListHandler(
            $this->getSession(),
            new CacheService('default'),
            0,
            $listRepo,
            new CSVParser(),
            $this->listDefinitions
        );
    }
 
    public function testGetMembers()
    {
        $listRepo = $this->getCtsListsMock('getCsvList', $this->correspondentList);
        $this->setListHander($listRepo);
        $this->ctsListHandler->initialiseList('ctsMemberList');
     
        $this->assertEquals(
            array("1234" => new Member($this->testMember), "4567" => new Member($this->fredMember)),
            $this->ctsListHandler->getList('ctsMemberList')
        );
    }
 
    public function testGetDcuCorrespondentStopList()
    {
        $listRepo = $this->getCtsListsMock('getCsvList', $this->correspondentList);
        $this->setListHander($listRepo);
        $this->ctsListHandler->initialiseList('dcuCorrespondentStopList');
     
        $this->assertEquals(
            array('Test member display' => 'Test member display'),
            $this->ctsListHandler->getList('dcuCorrespondentStopList')
        );
    }
 
    public function testGetPqCorrespondentStopList()
    {
        $listRepo = $this->getCtsListsMock('getCsvList', $this->correspondentList);
        $this->setListHander($listRepo);
        $this->ctsListHandler->initialiseList('pqCorrespondentStopList');
        $this->assertEquals(
            array('Fred Bloggs display' => 'Fred Bloggs display'),
            $this->ctsListHandler->getList('pqCorrespondentStopList')
        );
    }

    public function testExtractSelectedGroupForPersonQueryWithTeamAndUnitSet()
    {
        $listRepo = $this->getCtsListsMock('getDataList', $this->topicList);
        $this->setListHander($listRepo);
        $ctsCase = new CtsCase('workspace', 'store');
        $unit = 'GROUP_NAME';
        $team = 'TEAM_NAME';
        $ctsCase->setAssignedUnit($unit);
        $ctsCase->setAssignedTeam($team);

        $this->ctsListHandler->extractSelectedGroupForPersonQuery($ctsCase);
        $this->assertEquals($this->getSession()->get('groupForPersonQuery'), $team);
    }
 
    public function testExtractSelectedGroupForPersonQueryWithUnitSet()
    {
        $listRepo = $this->getCtsListsMock('getList', $this->topicList);
        $this->setListHander($listRepo);
        $ctsCase = new CtsCase('workspace', 'store');
        $unitsWithTeams = $this->createUnitWithTeam();
        $this->mockListsRepo('getUnitsAndTeams', $unitsWithTeams);
     
        $unit = 'GROUP_NAME';
        $team = 'TEAM_NAME';
        $ctsCase->setAssignedUnit($unit);
        $ctsCase->setAssignedTeam($team);

        $this->ctsListHandler->extractSelectedGroupForPersonQuery($ctsCase);
        $this->assertEquals($this->getSession()->get('groupForPersonQuery'), $team);
    }
 
    public function testExtractSelectedGroupForPersonQueryWithNoUnitOrTeamSet()
    {
        $listRepo = $this->getCtsListsMock('getList', $this->topicList);
        $this->setListHander($listRepo);
        $ctsCase = new CtsCase('workspace', 'store');
        $this->ctsListHandler->extractSelectedGroupForPersonQuery($ctsCase);
        $this->assertEquals($this->getSession()->get('groupForPersonQuery'), null);
    }
 
    public function testGetList()
    {
        $listRepo = $this->getCtsListsMock('getCsvList', $this->ministerList);
        $this->setListHander($listRepo);
        $ministerList = $this->ctsListHandler->getList('ctsMinisterList');
        $this->assertCount(2, $ministerList);
        $this->assertEquals(array('GROUP 1' => 'Minister 1', 'GROUP 2' => 'Minister 2'), $ministerList);
    }
}
