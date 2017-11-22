<?php

namespace HomeOffice\CtsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine;
use HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions;
use HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions;
use HomeOffice\AlfrescoApiBundle\Entity\StandardLinesPermissions;
use HomeOffice\AlfrescoApiBundle\Entity\AutoCreatePermissions;

class CtsWebTestCase extends WebTestCase
{

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client $client
     */
    protected $client = null;

    //@codingStandardsIgnoreStart
    protected $list = "\rItem 1, Item 1X, Item 1Y, Item 1Z, 123, true, true, true, true, true\rItem 2, Item 2X, Item 2Y, Item 2Z, 456, false, false, false, false, false";
    //@codingStandardsIgnoreEnd

    protected $dataList = '{
        "listName": "topicList",
        "listItems": [
            {
                "topicText": "Drugs",
                "pqStopList": true,
                "topicUnit": "GROUP_DCU"
            },
            {
                "topicText": "Alcohol",
                "pqStopList": false,
                "topicUnit": "GROUP_Parliamentary Questions Team"
            }
        ]
    }';

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->logIn();
        $this->mockPersonRepo('getUserDetails', $this->createPerson());

    }

    protected function getSession()
    {
        return $this->client->getContainer()->get('session');
    }

    private function createPerson()
    {
        $person = new \HomeOffice\AlfrescoApiBundle\Entity\Person();
        $person->setUserName('admin');
        $person->setUnits($this->createUnitWithTeam());
        $person->setTeams(array($this->createTeam()));
        $person->setCasesPermissions($this->createCasesPermissions());
        $person->setDocumentTemplatesPermissions($this->createDocumentTemplatesPermissions());
        $person->setStandardLinesPermissions($this->createStandardLinesPermissions());
        $person->setAutoCreatePermissions($this->createAutoCreatePermissions());

        return $person;
    }

    private function logIn()
    {
        $session = $this->getSession();
        $firewall = 'secured_area';
        $token = new UsernamePasswordToken($this->createPerson(), null, $firewall, array('ROLE_USER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function tearDown()
    {
        if ($this->client != null) {
            foreach ($this->client->getContainer()->getMockedServices() as $id => $service) {
                $this->client->getContainer()->unmock($id);
            }
            \Mockery::close();
            $this->client = null;
        }
        parent::tearDown();
    }

    protected function mockCtsCaseDocumentRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_document.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockBulkDocumentRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.bulk_document.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\BulkDocumentRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsCaseDocumentTemplateRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_document_template.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentTemplateRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsCaseStandardLineRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_standard_line.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseStandardLineRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsCaseMinuteRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_minute.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseMinuteRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsCaseRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsTsoFeedRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_tso_feed_upload.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseTsoFeedUploadRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsHelpDocumentRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_help_document.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsHelpDocumentRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockPersonRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.person.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\PersonRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockListsRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_lists.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsSearchRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_case_search.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsCaseSearchRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function mockCtsListsRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_lists.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function getCtsListsMock($functionName, $return)
    {
        return $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_lists.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs()
        ->getMock();
    }

    protected function mockCtsWorkflowRepo($functionName, $return)
    {
        $this->client->getContainer()->mock(
            'home_office_alfresco_api.cts_workflow.repository',
            'HomeOffice\AlfrescoApiBundle\Repository\CtsWorkflowRepository'
        )
        ->shouldReceive($functionName)
        ->andReturn($return)
        ->withAnyArgs();
    }

    protected function createUnitWithTeam()
    {
        return array($this->createUnit());
    }

    protected function createTeam()
    {
        $team = new Team();
        $team->setAuthorityName('TEAM_NAME');
        $team->setDisplayName('Team name');
        return $team;
    }

    protected function createCasesPermissions()
    {
        $permissions = new CasesPermissions();
        $permissions->setCanCreateFolder('true');
        return $permissions;
    }

    protected function createDocumentTemplatesPermissions()
    {
        $permissions = new DocumentTemplatesPermissions();
        $permissions->setCanCreateFolder('true');
        return $permissions;
    }

    protected function createStandardLinesPermissions()
    {
        $permissions = new StandardLinesPermissions();
        $permissions->setCanCreateFolder('true');
        return $permissions;
    }

    protected function createAutoCreatePermissions()
    {
        $permissions = new AutoCreatePermissions();
        $permissions->setCanCreateFolder('true');
        return $permissions;
    }

    protected function createUnit()
    {
        $unit = new Unit();
        $unit->setAuthorityName('GROUP_NAME');
        $unit->setDisplayName('Group name');
        $unit->setTeams(array($this->createTeam()));
        return $unit;
    }

    protected function createStandardLine()
    {
        $standardLine = new CtsCaseStandardLine('workspace', 'store');
        $standardLine->setAssociatedTopic('TOPIC_NAME');
        $standardLine->setAssociatedUnit('UNIT_NAME');
        $standardLine->setName('Test standard line');
        $standardLine->setId('1234');
        return $standardLine;
    }

    protected function mockSuccessCrawler()
    {
        $clientMock = $this->mockCrawler();

        $clientMock
            ->expects($this->any())->method('getStatusCode')
            ->willReturn('200');

        return $clientMock;
    }

    protected function mockFailureCrawler()
    {
        $clientMock = $this->mockCrawler();

        $clientMock
            ->expects($this->any())->method('getStatusCode')
            ->willReturn('400');

        return $clientMock;
    }

    private function mockCrawler()
    {
        $clientMock = $this->getMock(
            'Symfony\Bundle\FrameworkBundle\Client',
            array(
                'request',
                'getResponse',
                'getStatusCode',
                'filter',
                'count',
                'text',
                'getContent'
            ),
            array(
                $this->client->getKernel()
            )
        );

        $clientMock
            ->expects($this->any())->method('request')
            ->withAnyParameters()
            ->willReturnSelf();

        $clientMock
            ->expects($this->any())->method('getResponse')
            ->willReturnSelf();

        $clientMock
            ->expects($this->any())->method('filter')
            ->withAnyParameters()
            ->willReturnSelf();

        return $clientMock;
    }
}
