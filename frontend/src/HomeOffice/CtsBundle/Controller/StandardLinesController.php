<?php

namespace HomeOffice\CtsBundle\Controller;

use GuzzleHttp\Exception\RequestException;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\CtsBundle\Form\AjaxResponseBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;

/**
 * Class StandardLinesController
 *
 * @package HomeOffice\CtsBundle\Controller
 *
 * @Route("/tools/standard-lines")
 */
class StandardLinesController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageStandardLines', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $form = $this->createForm($this->get('home_office_cts.form.standard_line_search'), $request->query->all())->handleRequest($request);

        $standardLines = $this->getStandardLineRepository()->getStandardLines(
            isset($form->getData()['associatedUnit']) ? $form->getData()['associatedUnit'] : null,
            isset($form->getData()['associatedTopic']) ? $form->getData()['associatedTopic'] : null,
            isset($form->getData()['name']) ? $form->getData()['name'] : null
        );

        return [
            'standardLines' => $standardLines,
            'form'          => $form->createView(),
        ];
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function newAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageStandardLines', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $form = $this->createForm('CtsStandardLine', $this->getStandardLineFactory()->build([]));

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');
            $response->setForm($form);

            if ($form->isValid()) {
                $success = $this->getStandardLineRepository()->create($form->getData());

                if ($success === true) {
                    $response->setSuccess(true);
                    $response->setRedirectToRoute('homeoffice_cts_standardlines_index');
                } else {
                    $form->addError(new FormError(
                        is_array($success) ? $success['message'] : 'There was a problem adding the standard line.')
                    );
                }
            }

            return $response->createResponse();
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function editAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageStandardLines', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $standardLine = $this->getStandardLineRepository()->getStandardLine($request->get('id'));

        $form = $this->createForm('CtsStandardLine', $standardLine);

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');
            $response->setForm($form);

            if ($form->isValid()) {
                if ($standardLine->getFile()) {
                    $success = $this->getStandardLineRepository()->create($form->getData());
                } else {
                    $success = $this->getStandardLineRepository()->update($form->getData());
                }

                if ($success === true) {
                    $response->setSuccess(true);
                    $response->setRedirectToRoute('homeoffice_cts_standardlines_index');
                } else {
                    $form->addError(new FormError(
                        is_array($success) ? $success['message'] : 'There was a problem adding the standard line.')
                    );
                }
            }

            return $response->createResponse();
        }

        return [
            'standardLine' => $standardLine,
            'form'         => $form->createView(),
        ];
    }

    /**
     * @Route("/delete/{id}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function deleteAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageStandardLines', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $standardLine = $this->getStandardLineRepository()->getStandardLine($request->get('id'));

        if ($request->getMethod() == 'POST') {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');

            $success = $this->getStandardLineRepository()->deleteStandardLine($request->get('id'));

            $response->setSuccess($success);
            $response->setRedirectToRoute('homeoffice_cts_standardlines_index');

            return $response->createResponse();
        }


        return [
            'standardLine' => $standardLine,
        ];
    }

    /**
     * @Route("/download/{id}")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function downloadAction(Request $request)
    {
        $standardLine = $this->getStandardLineRepository()->getStandardLineFile($request->get('id'));
        $tmpFile = '/tmp/' . $standardLine->getNodeId();

        try {
            $guesser = MimeTypeGuesser::getInstance();
            $guesser->register(new FileinfoMimeTypeGuesser());
            $mimeType = $guesser->guess($tmpFile);
            if ($mimeType) {
                $standardLine->setExtension((new MimeTypeExtensionGuesser())->guess($mimeType));
            }
        } catch (\Exception $e) {

        }

        try {
            $response = new BinaryFileResponse($tmpFile);
            $response->trustXSendfileTypeHeader();
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $standardLine->getFileName());
        } catch (RequestException $e) {
            throw $this->createNotFoundException('The standard line is not available.');
        }

        return $response;
    }

    /**
     * @Route("/view/{caseType}")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $caseType = strtoupper($request->get('caseType'));
        $topics = $this->get('home_office_alfresco_api.service.topic')->getTopicNames($caseType);

        $standardLines = $this->getStandardLineRepository()->getStandardLinesByTopics($topics, $request->get('unit'));

        return [
            'caseType'      => $caseType,
            'standardLines' => $standardLines,
        ];
    }

    /**
     * @Route("/download-for-topic")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function downloadForTopicAction(Request $request)
    {
        $caseType = CaseCorrespondenceSubType::getCaseType($request->get('unit'));

        $standardLines = $this->getStandardLineRepository()->getStandardLines($caseType, $request->get('topic'));

        if (count($standardLines) !== 1) {
            return new JsonResponse([], 500);
        }

        return new JsonResponse(['id' => $standardLines[0]->getNodeId()]);
    }
}
