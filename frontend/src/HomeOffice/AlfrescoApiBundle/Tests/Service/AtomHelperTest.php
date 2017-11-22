<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\Paginator;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;

class AtomHelperTest extends ServiceTestHelper
{

    /**
     * @var AtomHelper
     */
    private $instance;
 
    // @codingStandardsIgnoreStart
    /**
     * @var string
     */
    private $createDcuEntryXml = '<?xml version="1.0" encoding="UTF-8"?>
<entry xmlns="http://www.w3.org/2005/Atom" xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/"><title>FolderName</title><cmisra:object><cmis:properties><cmis:propertyId propertyDefinitionId="objectTypeId"><cmis:value>F:cts:case</cmis:value></cmis:propertyId><cmis:propertyString propertyDefinitionId="cts:correspondenceType"><cmis:value>TYPE</cmis:value></cmis:propertyString><cmis:propertyDateTime propertyDefinitionId="cts:dateReceived"><cmis:value>2014-07-16T00:00:00+01:00</cmis:value></cmis:propertyDateTime><cmis:propertyBoolean propertyDefinitionId="cts:priority"><cmis:value>true</cmis:value></cmis:propertyBoolean></cmis:properties></cmisra:object></entry>';
 
    private $createPqEntryXml = '<?xml version="1.0" encoding="UTF-8"?>
<entry xmlns="http://www.w3.org/2005/Atom" xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/"><title>FolderName</title><cmisra:object><cmis:properties><cmis:propertyId propertyDefinitionId="objectTypeId"><cmis:value>F:cts:case</cmis:value></cmis:propertyId><cmis:propertyString propertyDefinitionId="cts:correspondenceType"><cmis:value>TYPE</cmis:value></cmis:propertyString><cmis:propertyDateTime propertyDefinitionId="cts:opDate"><cmis:value>2014-07-16T00:00:00+01:00</cmis:value></cmis:propertyDateTime></cmis:properties></cmisra:object></entry>';
    /**
     * @var string
     */
    private $singleCaseFeedXml = '<?xml version="1.0" encoding="utf-8"?>
                <entry xmlns="http://www.w3.org/2005/Atom" xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/">
                    <link rel="parent" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <link rel="self" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <object>
                        <properties>
                            <propertyId propertyDefinitionId="objectId">
                                <value>1234</value>
                            </propertyId>
                            <propertyString propertyDefinitionId="name">
                                <value>FolderName</value>
                            </propertyString>
                            <propertyString propertyDefinitionId="cts:correspondenceType">
                                <value>TYPE</value>
                            </propertyString>
                            <propertyDateTime propertyDefinitionId="cts:dateReceived">
                                <value>2014-07-16T00:00:00+01:00</value>
                            </propertyDateTime>
                            <propertyBoolean propertyDefinitionId="cts:priority">
                                <value>true</value>
                            </propertyBoolean>
                        </properties>
                        <allowableActions>
                            <cmis:canUpdateProperties>true</cmis:canUpdateProperties>
                            <cmis:canAssignUser>true</cmis:canAssignUser>
                        </allowableActions>
                    </object>
                </entry>';
 
    /**
     * @var string
     */
    private $multiCaseFeedXml = '<?xml version="1.0" encoding="utf-8"?>
            <feed xmlns="http://www.w3.org/2005/Atom" xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/">
                <opensearch:totalResults>2</opensearch:totalResults>
                <entry>
                    <link rel="parent" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <link rel="self" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <object>
                        <properties>
                            <propertyId propertyDefinitionId="objectId">
                                <value>1234</value>
                            </propertyId>
                            <propertyString propertyDefinitionId="name">
                                <value>FolderName</value>
                            </propertyString>
                            <propertyString propertyDefinitionId="cts:correspondenceType">
                                <value>TYPE</value>
                            </propertyString>
                            <propertyDateTime propertyDefinitionId="cts:dateReceived">
                                <value>2014-07-16T00:00:00+01:00</value>
                            </propertyDateTime>
                            <propertyBoolean propertyDefinitionId="cts:priority">
                                <value>true</value>
                            </propertyBoolean>
                        </properties>
                        <allowableActions>
                            <cmis:canUpdateProperties>true</cmis:canUpdateProperties>
                            <cmis:canAssignUser>true</cmis:canAssignUser>
                        </allowableActions>
                    </object>
                </entry>
                <entry>
                    <link rel="parent" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <link rel="self" href="http://localhost:8080/alfresco/service/cmis/s/workspace:SpacesStore/i/10866f20-fcd6-40fd-acb6-ef58f2003adc"/>
                    <object>
                        <properties>
                            <propertyId propertyDefinitionId="objectId">
                                <value>5678</value>
                            </propertyId>
                            <propertyString propertyDefinitionId="name">
                                <value>FolderName</value>
                            </propertyString>
                            <propertyString propertyDefinitionId="cts:correspondenceType">
                                <value>TYPE2</value>
                            </propertyString>
                            <propertyDateTime propertyDefinitionId="cts:dateReceived">
                                <value>2014-07-17T00:00:00+01:00</value>
                            </propertyDateTime>
                            <propertyBoolean propertyDefinitionId="cts:priority">
                                <value>false</value>
                            </propertyBoolean>
                        </properties>
                        <allowableActions>
                            <cmis:canUpdateProperties>false</cmis:canUpdateProperties>
                            <cmis:canAssignUser>false</cmis:canAssignUser>
                        </allowableActions>
                    </object>
                </entry>
            </feed>';
 
    private $caseProperties = array(
            'linkself' => array(
                'type' => 'link',
                'object_prop_name' => 'fileVersionUrl',
                'alfresco_prop_name' => 'linkself'
            ),
            'objectTypeId' => array(
                'object_prop_name' => 'objectTypeId',
                'alfresco_prop_name' => 'cmis:objectTypeId',
                'type' => 'id',
                'required_for_case_queue' => true
            ),
            'objectId' => array(
                'object_prop_name' => 'id',
                'alfresco_prop_name' => 'objectId',
                'type' => 'id',
                'system_field' => true,
                'required_for_case_queue' => true
            ),
            'name' => array(
                'object_prop_name' => 'folderName',
                'alfresco_prop_name' => 'name',
                'type' => 'string',
                'system_field' => true,
                'required_for_case_queue' => false
            ),
            'cts:correspondenceType' => array(
                'object_prop_name' => 'correspondenceType',
                'alfresco_prop_name' => 'cts:correspondenceType',
                'type' => 'string',
                'required_for_case_queue' => true
            ),
            'cts:dateReceived' => array(
                'object_prop_name' => 'dateReceived',
                'alfresco_prop_name' => 'cts:dateReceived',
                'type' => 'dateTime',
                'required_for_case_queue' => true
            ),
            'cts:priority' => array(
                'object_prop_name' => 'priority',
                'alfresco_prop_name' => 'cts:priority',
                'type' => 'boolean',
                'required_for_case_queue' => true
            ),
            'cts:opDate' => array(
                'object_prop_name' => 'opDate',
                'alfresco_prop_name' => 'cts:opDate',
                'type' => 'dateTime',
                'required_for_case_queue' => true
            ),
            'cts:opDate' => array(
                'object_prop_name' => 'opDate',
                'alfresco_prop_name' => 'cts:opDate',
                'type' => 'dateTime',
                'required_for_case_queue' => true
            )
        );
    // @codingStandardsIgnoreEnd
 
    private $casePermissions = array(
        'cmis:canUpdateProperties' => array(
                'object_prop_name' => 'canUpdateProperties',
                'alfresco_prop_name' => 'cmis:canUpdateProperties'
        ),
        'cmis:canAssignUser' => array(
                'object_prop_name' => 'canAssignUser',
                'alfresco_prop_name' => 'cmis:canAssignUser'
        )
    );
 
    protected function setUp()
    {
        $this->instance = new AtomHelper('cts', new CTSHelper('security.context', null));
    }
 
    private function generateDcuCase(
        $folderName,
        $correspondenceType,
        $dateReceived,
        $priority
    ) {
        $ctsCase = new CtsDcuMinisterialCase('workspace', 'store');
        $ctsCase->setFolderName($folderName);
        $ctsCase->setCorrespondenceType($correspondenceType);
        $ctsCase->setDateReceived($dateReceived);
        $ctsCase->setPriority($priority);
        return $ctsCase;
    }
 
    private function generatePqCase($folderName, $correspondenceType, $opDate)
    {
        $ctsCase = new CtsPqCase('workspace', 'store');
        $ctsCase->setFolderName($folderName);
        $ctsCase->setCorrespondenceType($correspondenceType);
        $ctsCase->setOpDate($opDate);
        return $ctsCase;
    }
 
    public function testGenerateDcuAtomEntry()
    {
        $ctsCase = $this->generateDcuCase('FolderName', 'TYPE', '16-07-2014', true);
        $atomXml = trim($this->instance->generateAtomEntry($ctsCase, $ctsCase->getFolderName(), $this->caseProperties));
        $this->assertEquals($this->createDcuEntryXml, $atomXml);
    }
 
    public function testGeneratePqAtomEntry()
    {
        $ctsCase = $this->generatePqCase('FolderName', 'TYPE', '16-07-2014');
        $atomXml = trim($this->instance->generateAtomEntry(
            $ctsCase,
            $ctsCase->getFolderName(),
            $this->caseProperties,
            'required_for_pq'
        ));
        $this->assertEquals($this->createPqEntryXml, $atomXml);
    }
 
    public function testMultiEntryFeedToArray()
    {
        $caseArray = $this->instance->multiEntryFeedToArray(
            $this->multiCaseFeedXml,
            'entry',
            $this->caseProperties,
            null,
            $this->casePermissions
        );
        $this->assertCount(2, $caseArray);
        $this->assertArrayHasKey('id', $caseArray[0]);
        $this->assertArrayHasKey('folderName', $caseArray[0]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[0]);
        $this->assertArrayHasKey('canUpdateProperties', $caseArray[0]);
        $this->assertArrayHasKey('canAssignUser', $caseArray[0]);
        $this->assertEquals($caseArray[0]['id'], '1234');
        $this->assertEquals($caseArray[0]['correspondenceType'], 'TYPE');
        $this->assertEquals($caseArray[0]['folderName'], 'FolderName');
        $this->assertEquals($caseArray[0]['canUpdateProperties'], 'true');
        $this->assertEquals($caseArray[0]['canAssignUser'], 'true');
        $this->assertArrayHasKey('id', $caseArray[1]);
        $this->assertArrayHasKey('folderName', $caseArray[1]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[1]);
        $this->assertArrayHasKey('canUpdateProperties', $caseArray[1]);
        $this->assertArrayHasKey('canAssignUser', $caseArray[1]);
        $this->assertEquals($caseArray[1]['id'], '5678');
        $this->assertEquals($caseArray[1]['correspondenceType'], 'TYPE2');
        $this->assertEquals($caseArray[1]['folderName'], 'FolderName');
        $this->assertEquals($caseArray[1]['canUpdateProperties'], 'false');
        $this->assertEquals($caseArray[1]['canAssignUser'], 'false');
    }
 
    public function testMultiEntryFeedToArrayPaginator()
    {
        $paginator = new Paginator();
        $caseArray = $this->instance->multiEntryFeedToArray(
            $this->multiCaseFeedXml,
            'object',
            $this->caseProperties,
            null,
            $this->casePermissions,
            $paginator
        );
        $this->assertEquals(2, $paginator->getTotalResults());
    }
 
    public function testMultiEntryFeedToArrayNoPermissions()
    {
        $caseArray = $this->instance->multiEntryFeedToArray(
            $this->multiCaseFeedXml,
            'entry',
            $this->caseProperties,
            null,
            null
        );
        $this->assertCount(2, $caseArray);
        $this->assertArrayHasKey('id', $caseArray[0]);
        $this->assertArrayHasKey('folderName', $caseArray[0]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[0]);
        $this->assertArrayNotHasKey('canUpdateProperties', $caseArray[0]);
        $this->assertArrayNotHasKey('canAssignUser', $caseArray[0]);
        $this->assertEquals($caseArray[0]['id'], '1234');
        $this->assertEquals($caseArray[0]['correspondenceType'], 'TYPE');
        $this->assertEquals($caseArray[0]['folderName'], 'FolderName');
        $this->assertArrayHasKey('id', $caseArray[1]);
        $this->assertArrayHasKey('folderName', $caseArray[1]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[1]);
        $this->assertArrayNotHasKey('canUpdateProperties', $caseArray[1]);
        $this->assertArrayNotHasKey('canAssignUser', $caseArray[1]);
        $this->assertEquals($caseArray[1]['id'], '5678');
        $this->assertEquals($caseArray[1]['correspondenceType'], 'TYPE2');
        $this->assertEquals($caseArray[1]['folderName'], 'FolderName');
    }
 
    public function testMultiEntryFeedToArrayNull()
    {
        $caseArray = $this->instance->multiEntryFeedToArray(
            null,
            'object',
            $this->caseProperties,
            null,
            $this->casePermissions
        );
        $this->assertCount(0, $caseArray);
    }
 
    public function testMultiEntryFeedToArrayRestrictProperties()
    {
        $caseArray = $this->instance->multiEntryFeedToArray(
            $this->multiCaseFeedXml,
            'object',
            $this->caseProperties,
            'required_for_case_queue',
            $this->casePermissions
        );
        $this->assertCount(2, $caseArray);
        $this->assertArrayHasKey('id', $caseArray[0]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[0]);
        $this->assertArrayHasKey('canUpdateProperties', $caseArray[0]);
        $this->assertArrayHasKey('canAssignUser', $caseArray[0]);
        $this->assertArrayNotHasKey('folderName', $caseArray[0]);
        $this->assertEquals($caseArray[0]['id'], '1234');
        $this->assertEquals($caseArray[0]['correspondenceType'], 'TYPE');
        $this->assertEquals($caseArray[0]['canUpdateProperties'], 'true');
        $this->assertEquals($caseArray[0]['canAssignUser'], 'true');
        $this->assertArrayHasKey('id', $caseArray[1]);
        $this->assertArrayHasKey('correspondenceType', $caseArray[1]);
        $this->assertArrayHasKey('canUpdateProperties', $caseArray[1]);
        $this->assertArrayHasKey('canAssignUser', $caseArray[1]);
        $this->assertArrayNotHasKey('folderName', $caseArray[1]);
        $this->assertEquals($caseArray[1]['id'], '5678');
        $this->assertEquals($caseArray[1]['correspondenceType'], 'TYPE2');
        $this->assertEquals($caseArray[1]['canUpdateProperties'], 'false');
        $this->assertEquals($caseArray[1]['canAssignUser'], 'false');
    }
 
    public function testSingleEntryFeedToArray()
    {
        $case = $this->instance->singleEntryFeedToArray(
            $this->singleCaseFeedXml,
            'object',
            $this->caseProperties,
            $this->casePermissions
        );
        $this->assertCount(7, $case);
        $this->assertArrayHasKey('id', $case);
        $this->assertArrayHasKey('folderName', $case);
        $this->assertArrayHasKey('correspondenceType', $case);
        $this->assertArrayHasKey('dateReceived', $case);
        $this->assertArrayHasKey('priority', $case);
        $this->assertArrayHasKey('canUpdateProperties', $case);
        $this->assertArrayHasKey('canAssignUser', $case);
        $this->assertEquals($case['id'], '1234');
        $this->assertEquals($case['folderName'], 'FolderName');
        $this->assertEquals($case['correspondenceType'], 'TYPE');
        $this->assertEquals($case['dateReceived'], '2014-07-16T00:00:00+01:00');
        $this->assertEquals($case['priority'], 'true');
        $this->assertEquals($case['canUpdateProperties'], 'true');
        $this->assertEquals($case['canAssignUser'], 'true');
    }
 
    public function testSingleEntryFeedToArrayReturnsFirstEntryInMultiFeed()
    {
        $case = $this->instance->singleEntryFeedToArray(
            $this->multiCaseFeedXml,
            'object',
            $this->caseProperties,
            $this->casePermissions
        );
        $this->assertCount(7, $case);
        $this->assertArrayHasKey('id', $case);
        $this->assertArrayHasKey('folderName', $case);
        $this->assertArrayHasKey('correspondenceType', $case);
        $this->assertArrayHasKey('dateReceived', $case);
        $this->assertArrayHasKey('priority', $case);
        $this->assertArrayHasKey('canUpdateProperties', $case);
        $this->assertArrayHasKey('canAssignUser', $case);
        $this->assertEquals($case['id'], '1234');
        $this->assertEquals($case['folderName'], 'FolderName');
        $this->assertEquals($case['correspondenceType'], 'TYPE');
        $this->assertEquals($case['dateReceived'], '2014-07-16T00:00:00+01:00');
        $this->assertEquals($case['priority'], 'true');
        $this->assertEquals($case['canUpdateProperties'], 'true');
        $this->assertEquals($case['canAssignUser'], 'true');
    }
}
