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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class DocumentTemplatesController
 *
 * @package HomeOffice\CtsBundle\Controller
 *
 * @Route("/tools/templates")
 */
class DocumentTemplatesController extends Controller
{
    const GENERAL_FILE_DOWNLOAD_ERROR = 'There was a problem downloading this file, please try again later.';
    const DELETE_FILE_ERROR = 'There was a problem deleting the document. Please try again later.';
    const EDIT_FILE_ERROR = 'There was a problem updating the template. Please try again later.';

    /**
     * @Route("/")
     * @Method({"GET"})
     * @Template
     *
     * @return RedirectResponse|array
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('manageTemplates', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        return [
            'templates' => $this->getCtsCaseDocumentTemplateRepository()->getDocumentTemplateObjects(null, false),
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
        if (false === $this->get('security.context')->isGranted('manageTemplates', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $form = $this->createForm('CtsDocumentTemplate', $this->getCtsCaseDocumentTemplateFactory()->build([]));

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');
            $response->setForm($form);

            if ($form->isValid()) {
                $success = $this->getCtsCaseDocumentTemplateRepository()->create($form->getData());

                if (is_bool($success) && $success) {
                    $response->setSuccess(true);
                    $response->setRedirectToRoute('homeoffice_cts_documenttemplates_index');
                } else {
                    $form->addError(new FormError(
                        is_array($success) ? $success['message'] : 'There was a problem adding your template.')
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
     * @Route("/edit/{nodeRef}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function editAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageTemplates', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $template = $this->getCtsCaseDocumentTemplateRepository()->getDocumentTemplate($request->get('nodeRef'));
        $template->setValidateFile(false);

        $form = $this->createForm('CtsDocumentTemplate', $template);

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');
            $response->setForm($form);

            if ($form->isValid()) {
                $this->getCtsCaseDocumentTemplateRepository()->update($template);
                $response->setSuccess(true);
                $response->setRedirectToRoute('homeoffice_cts_documenttemplates_index');
            }

            return $response->createResponse();
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/delete/{nodeRef}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response|array
     */
    public function deleteAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('manageTemplates', $this->getUser())) {
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $template = $this->getCtsCaseDocumentTemplateRepository()->getDocumentTemplate($request->get('nodeRef'));

        if ($request->getMethod() == 'POST') {
            /** @var AjaxResponseBuilder $response */
            $response = $this->get('home_office_cts.form.ajax_response_builder');

            $success = $this->getCtsCaseDocumentTemplateRepository()->deleteDocumentTemplate($request->get('nodeRef'));

            $response->setSuccess($success);
            $response->setRedirectToRoute('homeoffice_cts_documenttemplates_index');

            return $response->createResponse();
        }

        return [
            'template' => $template,
        ];
    }

    /**
     * @Route("/download/{nodeRef}/{caseNodeRef}", defaults={"caseNodeRef" = null})
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     *
     * @return Response
     */
    public function downloadAction(Request $request)
    {
        try {
            $template = $this->getCtsCaseDocumentTemplateRepository()->getDocumentTemplate($request->get('nodeRef'));
            $ctsCaseDocument = $this->getCtsCaseDocumentRepository()->getDocumentFile($template->getNodeId());

            if ($request->get('caseNodeRef')) {
                $case = $this->getCase($request->get('caseNodeRef'));
                $this->get('home_office_alfresco_api.cts_case_document.populator')->populate(
                    $case,
                    $ctsCaseDocument->getNodeId()
                );
            }

            $filename = $template->getDownloadFileName(
                $request->get('filenameTags', []),
                isset($case) ? $case : null
            );

            $response = new BinaryFileResponse('/tmp/' . $ctsCaseDocument->getNodeId());
            $response->trustXSendfileTypeHeader();
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        } catch (RequestException $e) {
            $response = new RedirectResponse($this->generateUrl('homeoffice_cts_documenttemplates_index'), 301);
        }

        return $response;
    }
}
