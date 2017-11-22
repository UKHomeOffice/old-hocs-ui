<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Connector;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Count;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Paginator;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SuperSearch
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 */
class SuperSearch
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var Connector
     */
    private $count;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var RepositoryInfo
     */
    private $repositoryInfo;

    /**
     * @var string
     */
    private $repoName = 'alfrescoRepositoryId';

    /**
     * @var array
     */
    private $select = [
        'c.cmis:objectTypeId',
        'c.cmis:objectId',
        'c.cts:correspondenceType',
        'c.cts:priority',
        'c.cts:advice',
        'c.cts:member',
        'c.cts:mpRef',
        'c.cts:replyToName',
        'c.cts:replyToPostcode',
        'c.cts:correspondentForename',
        'c.cts:correspondentSurname',
        'c.cts:correspondentPostcode',
        'c.cts:markupUnit',
        'c.cts:markupTopic',
        'c.cts:markupMinister',
        'c.cts:caseStatus',
        'c.cts:caseTask',
        'c.cts:urnSuffix',
        'c.cts:caseResponseDeadline',
        'c.cts:assignedUnit',
        'c.cts:assignedTeam',
        'c.cts:assignedUser',
        'c.cts:uin',
        'c.cts:opDate',
        'c.cts:homeSecretaryReply',
        'c.cts:signedByHomeSec',
        'c.cts:signedByLordsMinister',
        'c.cts:reviewedByPermSec',
        'c.cts:reviewedBySpads',
        'c.cts:foiMinisterSignOff',
        'c.cts:foiIsEir',
        'c.cts:foiDisclosure',
        'c.cts:caseRef',
        'c.cts:hmpoStage',
        'c.cts:hmpoPassportNumber',
        'c.cts:hmpoApplicationNumber',
        'c.cts:hoCaseOfficer',
        'c.cts:complex',
        'c.cts:icoReference',
        'c.cts:icoOutcome',
        'c.cts:icoComplaintOfficer',
        'c.cts:tsolRep',
        'c.cts:tribunalOutcome',
        'c.cts:organisation',
        'c.cts:draftDate',
        'c.cts:applicantForename',
        'c.cts:applicantSurname'
    ];

    /**
     * Constructor
     *
     * @param Mapper           $mapper
     * @param Connector        $connector
     * @param Count            $count
     * @param RepositoryInfo   $repositoryInfo
     * @param SessionInterface $session
     */
    public function __construct(
        Mapper $mapper,
        Connector $connector,
        Count $count,
        RepositoryInfo $repositoryInfo,
        SessionInterface $session
    ) {
        $this->mapper         = $mapper;
        $this->connector      = $connector;
        $this->count          = $count;
        $this->repositoryInfo = $repositoryInfo;
        $this->session        = $session;
    }

    /**
     * @param array $parameters
     * @param int   $limit
     * @param int   $offset
     *
     * @return Paginator
     */
    public function search(array $parameters, $limit = 10, $offset = 0)
    {
        $statement = $this->baseQuery()->map($parameters);

        $response = $this->connector->search(
            $this->getRepositoryId(),
            $statement,
            min($limit, 50),
            $offset
        );

        if ($response['numItems'] <= $limit && $offset == 0) {
            $numItems = $response['numItems'];
        } else {
            // Only make a second call for total items if the result count is less than the page size
            $numItems = $this->count->getCount($statement);
        }

        return new Paginator(
            $response['results'],
            $numItems,
            $response['hasMoreItems'],
            $limit,
            $offset
        );
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function export(array $parameters)
    {
        $response = $this->connector->search(
            $this->getRepositoryId(),
            $this->baseQuery()->map($parameters),
            1000,
            0
        );

        $cases = [];
        if ($response['results']) {
            foreach ($response['results'] as $result) {
                $deadline = new \DateTime();
                $deadline->setTimezone(new \DateTimeZone('UTC'))
                    ->setTimestamp($result['caseResponseDeadline'])
                    ->setTimezone(new \DateTimeZone('Europe/London'));

                $cases[] = [
                    'Case ID'        => $result['correspondenceType'] . '/' . $result['urnSuffix'],
                    'Final Deadline' => $deadline->format('d/m/Y'),
                    'Name/Member'    => $result['nameOrMember'],
                    'Unit'           => $result['markupUnit'],
                    'Topic'          => $result['markupTopic'],
                    'Status'         => $result['caseStatus'] . '(' .$result['caseTask'] . ')',
                    'Owner'          => $result['assignedUser'],
                ];
            }
        }

        return $cases;
    }

    /**
     * @return Mapper
     */
    private function baseQuery()
    {
        return $this->mapper
            ->select($this->select)
            ->from('cts:case as c')
            ->where('c.cts:caseStatus', CaseStatus::DELETED, '<>');
    }

    /**
     * @return string
     */
    public function getRepositoryId()
    {
        $repositoryId = $this->session->get($this->repoName);
        if (is_null($repositoryId)) {
            $repositoryId = $this->repositoryInfo->getRepositoryId();
            $this->session->set($this->repoName, $repositoryId);
        }

        return $repositoryId;
    }
}
