<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use HomeOffice\CtsBundle\Form\Type\CtsCaseDocumentTemplateType;
use HomeOffice\CtsBundle\Controller\DocumentHelper;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DocumentTemplateController extends Controller
{
    use DocumentHelper;
 
    const GENERAL_FILE_DOWNLOAD_ERROR = 'There was a problem downloading this file, please try again later.';
    const DELETE_FILE_ERROR = 'There was a problem deleting the document. Please try again later.';
    const EDIT_FILE_ERROR = 'There was a problem updating the template. Please try again later.';
 
    /**
     * @Template
     * @Route("/admin/manageDocumentTemplates")
     * @Method("GET")
     */
    public function manageAction()
    {
        if (false === $this->get('security.context')->isGranted('manageTemplates', $this->getUser())) {
            $this->setSessionParameter('errorMsg', 'You do not have permission to manage templates');
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }
        $documentTemplateError = $this->getSessionParameter('documentTemplateError');
        if (isset($documentTemplateError)) {
            $this->setSessionParameter('documentTemplateError', null);
        }
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $documentTemplates = $this->getDocumentTemplates(null, false);
        foreach ($documentTemplates as $documentTemplate) {
            $this->buildDeleteForm($documentTemplate);
        }
        return array(
            'documentTemplates' => $documentTemplates,
            'ctsHelper' => $ctsHelper,
            'documentTemplateError' => $documentTemplateError,
            'correspondenceSubTypes' => CaseCorrespondenceSubType::getCorrespondenceSubTypeArray()
        );
    }
 
    /**
     * @Template
     * @Route("/documentTemplate/download/{caseNodeRef}/{documentNodeRef}")
     * @Method("GET")
     *
     * @param string $caseNodeRef
     * @param string $documentNodeRef
     *
     * @return BinaryFileResponse
     */
    public function downloadFromCaseAction($caseNodeRef, $documentNodeRef)
    {
        return $this->download(
            $documentNodeRef,
            $caseNodeRef,
            'homeoffice_cts_case_view',
            ['nodeRef' => $caseNodeRef]
        );
    }
 
    /**
     * @Template
     * @Route("/documentTemplate/download/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadAction($documentNodeRef)
    {
        return $this->download($documentNodeRef, null, 'homeoffice_cts_documenttemplate_manage');
    }

    /**
     * @param string $documentNodeRef
     * @param string $caseNodeRef
     * @param string $returnPath
     * @param array $returnParams
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    private function download($documentNodeRef, $caseNodeRef, $returnPath, $returnParams = array())
    {
        if ($caseNodeRef != null) {
            $result = $this->downloadPopulatedTemplate($documentNodeRef, $caseNodeRef);
        } else {
            $result = $this->downloadDocument($documentNodeRef);
        }

        if ($result === false) {
            $this->setSessionParameter('errorMsg', self::GENERAL_FILE_DOWNLOAD_ERROR);

            return $this->redirect($this->generateUrl($returnPath, $returnParams));
        }

        return $this->createDocumentResponse(
            $result['nodeId'],
            $result['fileName'],
            ResponseHeaderBag::DISPOSITION_ATTACHMENT
        );
    }
 
    /**
     * Used only when adding a file from the add template screen.
     * Upon a successful upload the user will be redirected to the manage
     * templates screen.
     * @Template
     * @Route("admin/manageDocumentTemplates/add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $errorMsg = null;
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsCaseDocumentTemplate = new CtsCaseDocumentTemplate($workspace, $store);
        //@codingStandardsIgnoreStart
        $ctsCaseDocumentTemplateForm = $this->createForm(new CtsCaseDocumentTemplateType($workspace, $store, 'create'), $ctsCaseDocumentTemplate);
        //@codingStandardsIgnoreEnd
        $ctsCaseDocumentTemplateForm->handleRequest($request);

        if ($ctsCaseDocumentTemplateForm->isValid()) {
            $ctsCaseDocumentTemplateRepo = $this->get('home_office_alfresco_api.cts_case_document_template.repository');
            $response = $ctsCaseDocumentTemplateRepo->create($ctsCaseDocumentTemplate);
         
            if (is_bool($response) && $response) {
                return $this->redirect($this->generateUrl('homeoffice_cts_documenttemplate_manage'));
            } elseif (is_array($response)) {
                $errorMsg =  $response['message'];
            } else {
                $errorMsg =  'There was a problem adding your template.';
            }
        }
     
        return array(
            'ctsCaseDocumentTemplateForm' => $ctsCaseDocumentTemplateForm->createView(),
            'errorMsg' => $errorMsg,
            'formPurpose' => 'create'
        );
    }

    /**
     *
     * @Template
     * @Route("admin/manageDocumentTemplates/delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request)
    {
        $nodeRef = $request->request->get('form')['id'];
        $ctsCaseDocumentTemplateRepo = $this->get('home_office_alfresco_api.cts_case_document_template.repository');
        $response = $ctsCaseDocumentTemplateRepo->deleteDocumentTemplate($nodeRef);
        if (!$response) {
            $this->setSessionParameter('documentTemplateError', self::DELETE_FILE_ERROR);
        }
        return $this->redirect($this->generateUrl('homeoffice_cts_documenttemplate_manage'));
    }
 
    /**
     * @Template
     * @Route("/admin/manageDocumentTemplates/edit/{nodeRef}")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $nodeRef)
    {
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsCaseDocumentTemplateRepo = $this->get('home_office_alfresco_api.cts_case_document_template.repository');
        $ctsCaseDocumentTemplate = $ctsCaseDocumentTemplateRepo->getDocumentTemplate($nodeRef);
        $ctsCaseDocumentTemplate->setValidateFile(false);
     
        $ctsCaseDocumentTemplateForm = $this->createForm(
            new CtsCaseDocumentTemplateType($workspace, $store, 'edit'),
            $ctsCaseDocumentTemplate
        );
        $ctsCaseDocumentTemplateForm->handleRequest($request);
     
        if ($ctsCaseDocumentTemplateForm->isValid()) {
            $response = $ctsCaseDocumentTemplateRepo->update($ctsCaseDocumentTemplate);
            if (!$response) {
                $this->setSessionParameter('documentTemplateError', self::EDIT_FILE_ERROR);
            }
            return $this->redirect($this->generateUrl('homeoffice_cts_documenttemplate_manage'));
        }
     
        return array(
            'ctsCaseDocumentTemplateForm' => $ctsCaseDocumentTemplateForm->createView(),
            'documentNodeRef' => $nodeRef,
            'formPurpose' => 'edit'
        );
    }
 
    private function buildDeleteForm($ctsCaseDocumentTemplate)
    {
        $deleteForm = $this->createFormBuilder($ctsCaseDocumentTemplate)
            ->add('id', 'hidden', array('data' => $ctsCaseDocumentTemplate->getId()))
            ->add('delete', 'submit', array('label' => 'Delete', 'attr'=> array('class'=>'upload-delete-btn')))
            ->getForm();
        $ctsCaseDocumentTemplate->setDeleteForm($deleteForm->createView());
    }
}
