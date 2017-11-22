<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\AlfrescoApiBundle\Service\DocumentTemplateFileTags;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\ListBundle\Service\ListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CaseElementsController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class CaseElementsController extends Controller
{
    /**
     * @Route("/cases/parts/documentList/{nodeRef}")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @param CtsCase|null $case
     *
     * @return array
     */
    public function documentListAction(Request $request, CtsCase $case = null)
    {
        if (is_null($case) && $request->get('nodeRef')) {
            $case = $this->getCase($request->get('nodeRef'));
        }

        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        return [
            'case' => $case,
        ];
    }

    /**
     * @Route("/cases/parts/documentTemplateList/{nodeRef}")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @param CtsCase $case
     * @return array
     */
    public function documentTemplateListAction(Request $request, CtsCase $case)
    {
        if (is_null($case) && $request->get('nodeRef')) {
            $case = $this->getCase($request->get('nodeRef'));
        }

        return [
            'case' => $case,
            'documentTemplates' => $this->getCtsCaseDocumentTemplateRepository()->getDocumentTemplateObjects($case),
        ];
    }

    /**
     * @Route("/cases/parts/minutes/{nodeRef}")
     * @Method({"POST"})
     * @Template
     *
     * @param Request $request
     * @param CtsCase $case
     *
     * @return array|JsonResponse
     */
    public function minutesAction(Request $request, CtsCase $case = null)
    {
        if (is_null($case) && $request->getMethod() == 'POST') {
            $case = $this->getCase($request->get('nodeRef'));
        }

        $form = $this->createForm('CtsCaseMinute', $case->getNewMinute(), [
            'action' => $this->generateUrl('homeoffice_cts_caseelements_minutes', ['nodeRef' => $case->getNodeId()]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $ajaxResponseBuilder = $this->createAjaxFormResponse($form);
            if ($form->isValid()) {
                $this->getCtsCaseMinuteRepository()->create($form->getData(), $case->getNodeId());

                $table = $this->renderView('HomeOfficeCtsBundle:CaseElements:minutesTable.html.twig', [
                    'case'    => $this->getCase($case->getNodeId()),
                    'minutes' => $this->getCtsCaseMinuteRepository()->getMinutesForCase(
                        $case->getNodeId(),
                        ['manual', 'system']
                    )
                ]);

                $ajaxResponseBuilder
                    ->setSuccess(true)
                    ->setMessage('The new minute has been added to the case')
                    ->setCallback('updateNode', ['.minutesTable', json_encode($table)]);
            }

            return $ajaxResponseBuilder->createResponse();
        }

        return [
            'case'    => $case,
            'form'    => $form->createView(),
            'minutes' => $this->getCtsCaseMinuteRepository()->getMinutesForCase(
                $case->getNodeId(),
                ['manual', 'system']
            ),
        ];
    }

    /**
     * @Route("/cases/parts/allocate")
     * @Method({"POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return array|JsonResponse
     */
    public function allocateChoicesAction(Request $request)
    {
        $teams = [];
        $people = [];

        if ($request->get('filter') == 'me') {
            $units = [];
            /** @var Unit $unit */
            foreach ($this->getUser()->getUnits() as $unit) {
                // Add All the Users units
                $units[$unit->getAuthorityName()] = $unit->getDisplayName();
            }

            /** @var Team $team */
            foreach ($this->getUser()->getTeams() as $team) {
                $unit = $this->getListHandler()->getUnitFromTeam($team);
                if (!is_null($unit)) {
                    // Add All the Users inferred units
                    $units[$unit->getAuthorityName()] = $unit->getDisplayName();
                }

                // Add All the Users teams
                $teams[$team->getAuthorityName()] = $team->getDisplayName();
            }
        } else {
            $units = $this->get('home_office.list_service')->getUnitArray();

            if ($request->get('unit')) {
                /** @var Unit $unit */
                foreach ($this->getListHandler()->getList('ctsUnitAndTeamList') as $unit) {
                    if ($unit->getAuthorityName() === $request->get('unit')) {
                        /** @var Team $team */
                        foreach ($unit->getTeams() as $team) {
                            $teams[$team->getAuthorityName()] = $team->getDisplayName();
                        }
                    }
                }

                $group = $request->get('team') !== '' ? $request->get('team') : $request->get('unit');
                foreach ($this->getCtsListsRepository()->getPeopleFromGroup($group) as $person) {
                    $people[$person->getUserName()] = $person->getFullName();
                }
            }
        }

        return new JsonResponse([
            'units' => $units,
            'teams' => $teams,
            'people' => $people
        ], 200);
    }

    /**
     * @Template
     *
     * @param Request $request
     *
     * @return array
     */
    public function deadlineAction(Request $request)
    {
        return [
            'case' => $request->get('case'),
        ];
    }

    /**
     * @Route("/cases/parts/overview/{nodeRef}")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     *
     * @return array
     */
    public function overviewAction(Request $request)
    {
        /** @var CtsCase $case */
        $case = $request->get('case', $request->get('nodeRef') ? $this->getCase($request->get('nodeRef')) : null);

        $ministerList = $this->getListHandler()->getList('ctsMinisterList');
        if (array_key_exists($case->getMarkupMinister(), $ministerList)) {
            $case->setMarkupMinister($ministerList[$case->getMarkupMinister()]);
        }

        return [
            'case' => $case,
        ];
    }

    /**
     * @Route("/cases/parts/markup/teams")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return array|JsonResponse
     */
    public function markupTeamAction(Request $request)
    {
        /** @var ListService $listService */
        $listService = $this->get('home_office.list_service');

        try {
            return new JsonResponse($listService->getTeamArrayForUnit($request->get('unit')));
        } catch (\Exception $e) {
            return new JsonResponse(null, 500);
        }
    }

    /**
     * @Route("/cases/parts/document-table/{nodeRef}")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return array|JsonResponse
     */
    public function documentTableAction(Request $request)
    {
        $documents = [];

        try {
            /** @var CtsCaseDocument $document */
            foreach ($this->getCtsCaseDocumentRepository()->getDocumentsForCase($request->get('nodeRef'), true) as $document) {
                if ($request->get('filterTypes') &&
                    !in_array($document->getDocumentType(), explode(',', $request->get('filterTypes')))
                ) {
                    // Doesn't match our filters
                    continue;
                }

                $documents[] = [
                    'name'    => $document->getName(),
                    'type'    => $document->getDocumentType(),
                    'created' => (new \DateTime($document->getCreatedDate()))->format('d/m/Y\<\b\r\>ga'),
                    'link'    => $this->generateUrl('homeoffice_cts_document_download', [
                        'caseNodeRef'     => $request->get('nodeRef'),
                        'documentNodeRef' => $document->getNodeId(),
                    ]),
                    'nodeRef' => $document->getNodeId(),
                    'docPath' => $this->generateUrl($document->canDownloadDirect() ? 'homeoffice_cts_document_downloaddirect' : 'homeoffice_cts_document_downloadpdf', [
                        'documentNodeRef' => $document->getNodeId()
                    ])
                ];
            }
        } catch (\Exception $e) {
            return new JsonResponse(null, 500);
        }

        return new JsonResponse($documents, 200);
    }

    /**
     * @Route("/cases/parts/document-template-table/{nodeRef}/{correspondenceType}")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function documentTemplateTableAction(Request $request)
    {
        $templates = [];

        try {
            $correspondenceType = $request->get('correspondenceType');

            /** @var CtsCaseDocumentTemplate $template */
            foreach ($this->getDocumentTemplates($correspondenceType) as $template) {
                if ($request->get('filter')) {
                    if (strpos($template->getName(), $request->get('filter')) === false) {
                        continue;
                    }
                }

                $templates[] = [
                    'name'     => $template->getTemplateName(),
                    'filename' => $template->getName(),
                    'url'      => $this->generateUrl('homeoffice_cts_documenttemplates_download', [
                        'nodeRef'      =>  $template->getNodeId(),
                        'caseNodeRef'  => $request->get('nodeRef'),
                        'filenameTags' => DocumentTemplateFileTags::getForCorrespondenceType($correspondenceType),
                    ])
                ];

                usort($templates, function($a, $b) {
                    if ($a['name'] == $b['name']) {
                        return 0;
                    }
                    return ($a['name'] < $b['name']) ? -1 : 1;
                });
            }
        } catch (\Exception $e) {
            return new JsonResponse(null, 500);
        }

        return new JsonResponse($templates, 200);
    }

    /**
     * @Route("/cases/parts/markup-decisions/{nodeRef}")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function refreshMarkupDecisions(Request $request)
    {
        try {
            $case = $this->getCase($request->get('nodeRef'), false);

            if ($request->get('foiIsEir') === '0' || $request->get('foiIsEir') === '1') {
                /** @var CtsFoiCase $case */
                $case->setFoiIsEir($request->get('foiIsEir'));
            }

            return new JsonResponse(MarkupDecisions::getGuftDecisionList($case), 200);
        } catch (\Exception $e) {
            return new JsonResponse([]);
        }
    }

    /**
     * @Route("/cases/parts/topics")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return array|JsonResponse
     */
    public function topicsByUnitAction(Request $request)
    {
        try {
            return new JsonResponse($this->get('home_office_alfresco_api.service.topic')->getTopicsForForm(
                null,
                $request->get('unit') == '' ? null :$request->get('unit')
            ));
        } catch (\Exception $e) {
            return new JsonResponse(null, 500);
        }
    }
}
