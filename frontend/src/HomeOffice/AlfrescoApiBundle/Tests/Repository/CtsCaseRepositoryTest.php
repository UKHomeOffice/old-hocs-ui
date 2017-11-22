<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Repository;

use GuzzleHttp\Client;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\ListBundle\Service\ListHandler;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Class CtsCaseRepositoryTest
 *
 * @package HomeOffice\AlfrescoApiBundle\Tests\Repository
 */
class CtsCaseRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client|Mock
     */
    private $client;

    /**
     * @var CtsCaseFactory|Mock
     */
    private $ctsCaseFactory;

    /**
     * @var SessionTicketStorage|Mock
     */
    private $sessionTicketStorage;

    /**
     * @var AtomHelper|Mock
     */
    private $atomHelper;

    /**
     * @var CTSHelper|Mock
     */
    private $ctsHelper;

    /**
     * @var QueryHelper|Mock
     */
    private $queryHelper;

    /**
     * @var Session|Mock
     */
    private $session;

    /**
     * @var ListHandler|Mock
     */
    private $listHandler;

    /**
     * Test Assign Case To Person Returns Case When Person Has No Unit Or Team
     */
    public function testAssignCaseToPersonReturnsCaseWhenPersonHasNoUnitOrTeam()
    {
        $person = new Person();
        $person->setUserName('Person Username');

        $case = new CtsCase('workspace', 'store');

        $this->assertSame($case, $this->getCaseRepository()->assignCaseToPerson($case, $person));
        $this->assertFalse($case->getAssignedUser() == $person->getUserName(), 'The user should not be assigned');
    }

    /**
     * Test Assign Case To Person Returns Case When Person Has Team No Unit
     */
    public function testAssignCaseToPersonReturnsCaseWhenPersonHasTeamNoUnit()
    {
        $unit = new Unit();
        $unit->setAuthorityName('Unit From List Handler');

        $team = new Team();
        $team->setAuthorityName('Team Assigned To Person');

        $person = new Person();
        $person->setTeams([$team]);

        $case = new CtsCase('workspace', 'store');

        $this->listHandler->expects($this->once())->method('getUnitFromTeam')->with($team)->willReturn($unit);

        $this->assertSame($case, $this->getCaseRepository()->assignCaseToPerson($case, $person));
        $this->assertSame($person->getUserName(), $case->getAssignedUser(), 'The User should be assigned');
        $this->assertSame($unit->getAuthorityName(), $case->getAssignedUnit(), 'The Unit should be assigned');
        $this->assertSame($team->getAuthorityName(), $case->getAssignedTeam(), 'The Team should be assigned');
    }

    /**
     * Test Assign Case To Person Returns Case When Person Has Team And Unit
     */
    public function testAssignCaseToPersonReturnsCaseWhenPersonHasTeamAndUnit()
    {
        $unit = new Unit();
        $unit->setAuthorityName('Unit Assigned To Person');

        $team = new Team();
        $team->setAuthorityName('Team Assigned To Person');

        $person = new Person();
        $person->setTeams([$team]);
        $person->setUnits([$unit]);

        $case = new CtsCase('workspace', 'store');

        $this->assertSame($case, $this->getCaseRepository()->assignCaseToPerson($case, $person));
        $this->assertSame($person->getUserName(), $case->getAssignedUser(), 'The User should be assigned');
        $this->assertSame($unit->getAuthorityName(), $case->getAssignedUnit(), 'The Unit should be assigned');
        $this->assertSame($team->getAuthorityName(), $case->getAssignedTeam(), 'The Team should be assigned');
    }

    /**
     * @param string $workspace
     * @param string $store
     * @param array  $properties
     * @param array  $permissions
     *
     * @return CtsCaseRepository
     */
    protected function getCaseRepository($workspace = 'workspace', $store = 'store', $properties = [], $permissions = [])
    {
        return new CtsCaseRepository(
            $this->client,
            $this->ctsCaseFactory,
            $this->sessionTicketStorage,
            $this->atomHelper,
            $this->ctsHelper,
            $this->queryHelper,
            $workspace,
            $store,
            $properties,
            $permissions,
            $this->session,
            $this->listHandler
        );
    }

    protected function setUp()
    {
        $this->client = $this->setUpMock(Client::class);
        $this->ctsCaseFactory = $this->setUpMock(CtsCaseFactory::class);
        $this->sessionTicketStorage = $this->setUpMock(SessionTicketStorage::class);
        $this->atomHelper = $this->setUpMock(AtomHelper::class);
        $this->ctsHelper = $this->setUpMock(CTSHelper::class);
        $this->queryHelper = $this->setUpMock(QueryHelper::class);
        $this->session = $this->setUpMock(Session::class);
        $this->listHandler = $this->setUpMock(ListHandler::class);
    }

    /**
     * @param string $class
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function setUpMock($class)
    {
        return $this->getMockBuilder($class)->disableOriginalConstructor()->getMock();
    }
}
