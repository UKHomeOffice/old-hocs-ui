<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\HttpFoundation\CsvResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SuperSearchController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class SuperSearchController extends CtsController
{
    /**
     * Results Action
     *
     * @Route("/search/results")
     * @Method({"GET"})
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function resultsAction(Request $request)
    {
        $businessUnit = $request->get('businessUnit');

        $form = $this->getForm($businessUnit)->handleRequest($request);

        parse_str($request->getQueryString(), $queryArray);

        return [
            'dashboard'  => $request->get('dashboard', false),
            'queryArray' => $queryArray,
            'form'       => $form->createView(),
            'search'     => $this->get('home_office_alfresco_api.consumer.super_search')->search(
                $form->getData(),
                $request->get('limit', 20),
                $request->get('offset', 0)
            ),
        ];
    }


    /**
     * Export Action
     *
     * @Route("/search/export")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $data = $this->get('home_office_alfresco_api.consumer.super_search')->export(
            $this->getForm($request->get('businessUnit'))->handleRequest($request)->getData()
        );

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$request->get('name').'"',
        ];

        return new CsvResponse($data,200, $headers, true);
    }

    /**
     * Search Action
     *
     * @Route("/search/{businessUnit}", defaults={"businessUnit" = "all"})
     * @Method({"GET"})
     * @Template()
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function searchAction(Request $request)
    {
        $businessUnit = $request->get('businessUnit');
        $form = $this->getForm($businessUnit, $this->tidyRequestValues($request));

        if ($request->isXmlHttpRequest()) {
            return $this->render('HomeOfficeCtsBundle:SuperSearch:Advanced/'.$businessUnit.'.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return [
            'form'         => $form->createView(),
            'businessUnit' => $businessUnit,
            'title'        => 'Search',
            'breadcrumb'   => [
                'Home'          => 'homeoffice_cts_home_home',
                'Global Search' => 'homeoffice_cts_supersearch_search'
            ]
        ];
    }

    /**
     * @param string $businessUnit
     * @param array $data
     *
     * @return Form
     */
    private function getForm($businessUnit, array $data = [])
    {
        try {
            $type = $this->get('home_office_cts.form.super_search.' . strtolower($businessUnit));
        } catch (ServiceNotFoundException $e) {
            $type = $this->get('home_office_cts.form.super_search.all');
        }

        return $this->createForm($type, $data);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function tidyRequestValues(Request $request)
    {
        $returnValues = [];

        if ($request->get('businessUnit')) {
            $returnValues['businessUnit'] = $request->get('businessUnit');
        }

        foreach($request->query->all() as $key => $value) {
            $returnValues[$key] = $this->tidyDateTime($value);
        }

        return $returnValues;
    }

    /**
     * @param mixed $value
     *
     * @return string|\DateTime
     */
    private function tidyDateTime($value)
    {
        return is_array($value) && isset($value['day'], $value['month'], $value['year'])
            ? (new \DateTime())->setDate($value['year'], $value['month'], $value['day'])
            : $value;
    }
}
