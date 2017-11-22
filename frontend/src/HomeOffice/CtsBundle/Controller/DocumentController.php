<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\CtsBundle\Form\Type\CtsCaseDocumentType;
use HomeOffice\CtsBundle\Controller\DocumentHelper;

class DocumentController extends Controller
{
    use DocumentHelper;
 
    const GENERAL_FILE_DOWNLOAD_ERROR      = 'There was a problem downloading this file, please try again later.';
    const DELETE_DOCUMENT_PERMISSION_ERROR = 'You do not have permission to delete this document';
    const PROBLEM_DELETING_DOCUMENT_ERROR  = 'There was a problem deleting the document. Please try again later.';
    const UPLOAD_FILE_CHANGED_TYPE_ERROR   = 'The document to upload is not the same type as the original document.';

    /**
     * @Template
     * @Route("/document/download/{caseNodeRef}/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadAction($caseNodeRef, $documentNodeRef)
    {
        $result = $this->downloadDocument($documentNodeRef);
        if (is_bool($result) && !$result) {
            $this->setSessionParameter('documentErrorMsg', self::GENERAL_FILE_DOWNLOAD_ERROR);
            return $this->redirect($this->generateUrl('homeoffice_cts_case_view', array('nodeRef' => $caseNodeRef)));
        }
        $nodeId = $result['nodeId'];
        $fileName = $result['fileName'];
        return $this->createDocumentResponse($nodeId, $fileName);
    }
 
    /**
     * @Template
     * @Route("/document/download/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadDirectAction($documentNodeRef)
    {
        $result = $this->downloadDocument($documentNodeRef);
        if (is_bool($result) && !$result) {
            return $this->createDocumentPreviewFailedResponse();
        }
        $nodeId = $result['nodeId'];
        $fileName = $result['fileName'];
        return $this->createDocumentResponse($nodeId, $fileName);
    }
 
    /**
     * @Template
     * @Route("/document/downloadVersion")
     * @Method("GET")
     */
    public function downloadVersionAction(Request $request)
    {
        $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
     
        $fileVersionUrl = $request->query->get('fileVersionUrl');
        $documentNodeRef = $request->query->get('documentNodeRef');
        $documentName = $request->query->get('documentName');

        $result = $ctsCaseDocumentRepository->getDocumentVersionByUrl($fileVersionUrl, $documentNodeRef);
        if (is_bool($result) && !$result) {
            $this->setSessionParameter('documentErrorMsg', self::GENERAL_FILE_DOWNLOAD_ERROR);
        } else {
            return $this->createDocumentResponse($documentNodeRef, $documentName);
        }
    }
 
    /**
     * @Template
     * @Route("/document/downloadRendition/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadPdfAction($documentNodeRef)
    {
        $result = $this->downloadDocumentPreview($documentNodeRef, 'PDF');
        if (is_bool($result) && !$result) {
            return $this->createDocumentPreviewFailedResponse();
        }
        $nodeId = $result['nodeId'];
        $fileName = $result['fileName'];
        return $this->createDocumentResponse($nodeId, $fileName);
    }
 
    /**
     * @Template
     * @Route("/document/downloadImg/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadImgAction($documentNodeRef)
    {
        $result = $this->downloadDocumentPreview($documentNodeRef, 'image');
        if (is_bool($result) && !$result) {
            return $this->createDocumentPreviewFailedResponse();
        }
        $nodeId = $result['nodeId'];
        $fileName = $result['fileName'];
        return $this->createDocumentResponse($nodeId, $fileName);
    }
 
    /**
     * Used only when adding a file from the view screen.
     * Upon a successful upload the user will be redirected to the view case screen.
     * @Template
     * @Route("/document/add")
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        // use CtsCase super type here to generically add docs to any case type
        $ctsCase = new CtsCase($workspace, $store);
        $caseId = $request->request->get('ctsCaseDocument')['id'];
        $ctsCase->setId($caseId);
     
        $ctsCaseDocument = new CtsCaseDocument($workspace, $store);
        $ctsCaseDocumentType = new CtsCaseDocumentType('view', $workspace, $store, $ctsCase->getId());
        $ctsCaseDocumentForm = $this->createForm($ctsCaseDocumentType, $ctsCaseDocument);
        $ctsCaseDocumentForm->handleRequest($request);
     
        $redirect = $request->query->get('redirect');
     
        if ($ctsCaseDocumentForm->isValid()) {
            $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
            $result = $ctsCaseDocumentRepository->create($ctsCaseDocument, $ctsCase->getId(), $ctsCase->getNodeId());
            if (is_string($result)) {
                $this->setSessionParameter('documentErrorMsg', $result);
            }
        } else {
            // if the document is not valid, add it to the session to the entered values can be re-shown
            if ($ctsCaseDocument->getFile() != null) {
                $ctsCaseDocument->setName($ctsCaseDocument->getFile()->getClientOriginalName());
                $ctsCaseDocument->setFile(null);
            }
            $this->setSessionParameter('ctsCaseDocument', $ctsCaseDocument);
        }
     
        if ($redirect == 'detailedDocument') {
            return $this->redirect($this->generateUrl(
                'homeoffice_cts_document_getdocumentlist',
                array('nodeRef' => $ctsCase->getNodeId())
            ));
        }
        return $this->redirect($this->generateUrl(
            'homeoffice_cts_case_view',
            array('nodeRef' => $ctsCase->getNodeId())
        ));
    }

    /**
     * @Template("HomeOfficeCtsBundle:DetailedDocuments:detailedDocumentList.html.twig")
     * @Route("/cases/documents/fragment/{nodeRef}")
     * @Method({"GET"})
     */
    public function getDocumentListAjaxAction(Request $request, $nodeRef)
    {
        return $this->getDocumentListAction($request, $nodeRef);
    }
 
    /**
     * @Template("HomeOfficeCtsBundle:DetailedDocuments:detailedDocumentList.html.twig")
     * @Route("/cases/documents/{nodeRef}")
     * @Method({"GET"})
     */
    public function getDocumentListAction(Request $request, $nodeRef)
    {
        $documentErrorMsg = $this->getSessionParameter('documentErrorMsg');
        if (isset($documentErrorMsg)) {
            $this->setSessionParameter('documentErrorMsg', null);
        }
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
        $ctsCaseRepository = $this->get('home_office_alfresco_api.cts_case.repository');
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');

        $documentVersions = null;
        $documentRefForVersion = $request->query->get('documentRefForVersion');

        if ($request->query->get('documentRefForVersion') != null) {
            $documentVersions = $ctsCaseDocumentRepository->getDocumentVersions($documentRefForVersion);
        }
     
        $ctsCase = $ctsCaseRepository->getCase($nodeRef);
        $ctsCase->setCaseDocuments($ctsCaseDocumentRepository->getDocumentsForCase($nodeRef));

     
        $ctsCaseDocument = $this->getSessionParameter('ctsCaseDocument');
        $ctsCaseDocumentForm = $this->createForm(
            new CtsCaseDocumentType('view', $workspace, $store, $ctsCase->getId()),
            $ctsCaseDocument,
            array('action' => $this->generateUrl(
                'homeoffice_cts_document_add',
                array('redirect' => 'detailedDocument')
            ))
        );

        if (isset($ctsCaseDocument)) {
            $this->populateCaseDocumentFormErrors($ctsCaseDocument, $ctsCaseDocumentForm);
            $this->setSessionParameter('ctsCaseDocument', null);
        }

        foreach ($ctsCase->getCaseDocuments() as $document) {
            $this->buildAddVersionForm($document, $ctsCase->getNodeId());
            $this->buildDeleteForm($document, $ctsCase->getNodeId());
        }
     
        return array(
            'ctsCase' => $ctsCase,
            'ctsHelper' => $ctsHelper,
            'caseNodeRef' => $nodeRef,
            'documentRefForVersion' => $documentRefForVersion,
            'documentVersions' => $documentVersions,
            'ctsCaseDocumentForm' => $ctsCaseDocumentForm->createView(),
            'documentErrorMsg' => $documentErrorMsg
        );
    }
 
    /**
     * Used to upload a new document version
     * Upon a successful upload the user will be redirected to the detailed document case screen.
     * @Template
     * @Route("/cases/document/upload")
     * @Method({"POST"})
     */
    public function uploadDocumentVersionAction(Request $request)
    {
        $workspace       = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store           = $this->container->getParameter('home_office_alfresco_api.store');
        $documentNodeRef = $request->query->get('documentNodeRef');

        $ctsCaseId        = $request->request->get('upload_' . $documentNodeRef)['caseId'];
        $documentId       = $request->request->get('upload_' . $documentNodeRef)['id'];
        $originalMimeType = $request->request->get('upload_' . $documentNodeRef)['mimeType'];

        $ctsCaseDocument = new CtsCaseDocument($workspace, $store);
        $ctsCaseDocument->setFile($request->files->get('upload_' . $documentNodeRef)['file']);
        $ctsCaseDocument->setId($documentId);

        $redirectParams = array(
            'nodeRef'               => $ctsCaseId,
            'documentRefForVersion' => $request->get('documentRefForVersion'),
        );

        if ($ctsCaseDocument->getFile()->getClientMimeType() != $originalMimeType) {
            $this->setSessionParameter('documentErrorMsg', self::UPLOAD_FILE_CHANGED_TYPE_ERROR);
        } else {
            $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
            $result = $ctsCaseDocumentRepository->uploadNewDocumentVersion(
                $ctsCaseDocument,
                $ctsCaseId
            );

            if (is_string($result)) {
                $this->setSessionParameter('documentErrorMsg', $result);
            }
        }


        if ($request->get('ajax') == 'true') {
            $redirectParams['docRef'] = $documentId;

            return $this->redirect(
                $this->generateUrl(
                    'homeoffice_cts_document_getdocumentlistajax',
                    $redirectParams
                )
            );
        } else {
            return $this->redirect(
                $this->generateUrl(
                    'homeoffice_cts_document_getdocumentlist',
                    $redirectParams
                )
            );
        }
    }

    /**
     *
     * @Template
     * @Route("/document/delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request)
    {
        $ctsCaseRepository = $this->get('home_office_alfresco_api.cts_case.repository');

        $documentNodeRef = $request->query->get('documentNodeRef');
        $ctsCaseId = $request->request->get('del_'.$documentNodeRef)['caseId'];
        $documentId = $request->request->get('del_'.$documentNodeRef)['id'];
             
        $ctsCase = $ctsCaseRepository->getCase($ctsCaseId);
     
        $ctsCaseDocumentRepo = $this->get('home_office_alfresco_api.cts_case_document.repository');

        if (false === $this->get('security.context')->isGranted('delete', $ctsCase)) {
            $this->setSessionParameter('documentErrorMsg', self::DELETE_DOCUMENT_PERMISSION_ERROR);
        } else {
            $response = $ctsCaseDocumentRepo->deleteDocument($documentId);
            if (!$response) {
                $this->setSessionParameter('documentErrorMsg', self::PROBLEM_DELETING_DOCUMENT_ERROR);
            }
        }
        $redirectParams = array(
            'nodeRef' => $ctsCaseId,
            'documentRefForVersion' => $request->get('documentRefForVersion'),
        );
        if ($request->get('ajax') == 'true') {
            return $this->redirect($this->generateUrl(
                'homeoffice_cts_document_getdocumentlistajax',
                $redirectParams
            ));
        } else {
            return $this->redirect($this->generateUrl(
                'homeoffice_cts_document_getdocumentlist',
                $redirectParams
            ));
        }
    }
 
    /**
     *
     * @param CtsCaseDocument $ctsCaseDocument
     * @param string $caseId
     */
    private function buildAddVersionForm($ctsCaseDocument, $caseId)
    {
        $addVersionForm = $this->get('form.factory')->createNamedBuilder(
            'upload_' . $ctsCaseDocument->getNodeId(),
            'form',
            $ctsCaseDocument
        )
            ->add('id', 'hidden', array('data' => $ctsCaseDocument->getId()))
            ->add('caseId', 'hidden', array('data' => $caseId, 'mapped' => false))
            ->add('mimeType', 'hidden', array('data' => $ctsCaseDocument->getMimeType(), 'mapped' => false))
            ->add('file', 'file', array('label' => '+', 'attr'=> array('class'=>'add_version_button dont-replace')))
            ->add('submit', 'submit', array('label' => 'Upload', 'attr'=> array('class'=>'upload_version_button')))
            ->getForm();

        $view = $addVersionForm->createView();
        $ctsCaseDocument->setAddVersionForm($view);
    }
 
    /**
     *
     * @param CtsCaseDocument $ctsCaseDocument
     * @param string $caseId
     */
    private function buildDeleteForm($ctsCaseDocument, $caseId)
    {
        $deleteForm = $this->get('form.factory')->createNamedBuilder(
            'del_' . $ctsCaseDocument-> getNodeId(),
            'form',
            $ctsCaseDocument
        )
            ->add('id', 'hidden', array('data' => $ctsCaseDocument->getId()))
            ->add('caseId', 'hidden', array('data' => $caseId, 'mapped' => false))
            ->add('delete', 'submit', array('label' => 'Delete', 'attr'=> array('class'=>'upload-delete-btn')))
            ->getForm();
        $ctsCaseDocument->setDeleteForm($deleteForm->createView());
    }
}
