<?php
/**
 * Created by IntelliJ IDEA.
 * User: ben.diggle
 * Date: 29/10/15
 */

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\CtsBundle\Form\Type\CtsCaseSearchByFieldType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PQSearchController extends Controller
{

    /**
     * @Template
     * @Route("/pqSearch")
     * @Method({"GET","POST"})
     */
    public function pqSearchAction(Request $request)
    {
        /** @var ListHandler $ctsListHander */
        $ctsListHander           = $this->get('home_office_list.handler');
        /** @var CtsCaseSearchByFieldType $searchByFieldType */
        $searchByFieldType       = new CtsCaseSearchByFieldType($ctsListHander, true);
        $searchByForm = $this->createForm($searchByFieldType);
        $searchByForm->handleRequest($request);

        $skip = 0;

        $searchByForm = $this->createForm($searchByFieldType);

        return array(
            'searchByForm' => $searchByForm->createView(),
            'skip' => $skip);
    }

    /**
     * @Template
     * @Route("/pqSearch/searchAjax")
     * @Method({"GET","POST"})
     */
    public function pqSearchAJAXAction(Request $request)
    {
        $searchParams = $request->request->get('params');
        $searchParams = json_encode($searchParams);
        $pqSearchResults = array();
        /** @var ListHandler $ctsListHander */
        $ctsCaseSearchRepository = $this->get('home_office_alfresco_api.cts_case_search.repository');

        $searchResultsObject = $ctsCaseSearchRepository->pqSearchResults($searchParams);
        $searchResultsJSON = json_decode($searchResultsObject, true);
        $searchList = $searchResultsJSON['searchList'];

        foreach ($searchList as $searchObj) {
            $decodeResults = json_decode($searchObj, true);
            array_push($pqSearchResults, $decodeResults);
        }

        return new JsonResponse($pqSearchResults);
    }
}
