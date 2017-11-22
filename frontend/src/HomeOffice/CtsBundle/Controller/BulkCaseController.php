<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Factory\BulkDocumentFactory;
use HomeOffice\AlfrescoApiBundle\Repository\BulkDocumentRepository;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeOffice\AlfrescoApiBundle\Entity\BulkCaseEntry;
use HomeOffice\AlfrescoApiBundle\Entity\BulkDocument;
use HomeOffice\CtsBundle\Form\Type\BulkCaseEntryType;

/**
 * Class BulkCaseController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class BulkCaseController extends Controller
{
    use DocumentHelper;

    const FORM_ERROR = 'Form not valid, please ensure you have selected the case type and added files.';
    const DELETE_FILE_ERROR = 'There was a problem deleting the document. Please try again later.';
    const NO_PERMISSIONS_ERROR = 'You do not have permissions for bulk create cases.';

    /**
     * @return array|RedirectResponse
     *
     * @Template
     * @Route("/cases/bulk/create")
     * @Method("GET")
     */
    public function createAction()
    {
        if (false === $this->get('security.context')->isGranted('bulkCreateCases', $this->getUser())) {
            $this->setSessionParameter('errorMsg', self::NO_PERMISSIONS_ERROR);
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        $form = $this->createForm(new BulkCaseEntryType($this->getListHandler()));

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Template
     * @Route("/cases/bulk/assignment")
     * @Method("GET")
     */
    public function assigneeAction(Request $request)
    {
        $options = [];

        if (strstr($request->get('field'), 'assignedUnit')) {
            $updateField = 'ctsBulkCaseEntry_assignedTeam';
            $emptyValue = 'Please select a team';

            /** @var Team $option */
            foreach ($this->getListHandler()->getTeamsFromUnit($request->get('value')) as $option) {
                $options[] = [
                    'key'   => $option->getAuthorityName(),
                    'value' => $option->getDisplayName(),
                ];
            }
        } else {
            $updateField = 'ctsBulkCaseEntry_assignedUser';
            $emptyValue = 'Please select a user';

            $this->setSessionParameter('groupForPersonQuery', $request->get('value'));
            foreach ($this->getListHandler()->getPeopleFromUnitOrTeam() as $option) {
                $options[] = [
                    'key'   => $option->getUserName(),
                    'value' => $option->getFullName(),
                ];
            }
        }

        return new JsonResponse([
            'updateField' => $updateField,
            'options'     => $options,
            'emptyValue'  => $emptyValue
        ]);
    }

    /**
     * @Route("/cases/bulk/upload")
     * @Method("POST")
     *
     * @param  Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $bulkCaseEntry = new BulkCaseEntry();

        $form = $this->createForm(new BulkCaseEntryType($this->getListHandler()), $bulkCaseEntry);

        if ($form->handleRequest($request)->isValid() === false) {
            return new Response(self::FORM_ERROR, 400);
        }

        $files = [];
        $errors = [];

        foreach ($bulkCaseEntry->getFiles() as $file) {
            $result = $this->getBulkDocumentRepository()->create(
                $this->get('home_office_alfresco_api.bulk_document.factory')->build(['file' => $file]),
                $bulkCaseEntry->getCorrespondenceType(),
                $request->get('assignedUnit'),
                $request->get('assignedTeam'),
                $request->get('assignedUser')
            );

            if ($result === true) {
                $files[] = ['name' => $file->getClientOriginalName()];
            } else if (is_array($result)) {
                $errors[] = $result['message'];
            }
        }

        if (count($errors) == 0) {
            return new JsonResponse(['files' => $files]);
        }

        return new Response(implode(', ', $errors), 400);
    }

    /**
     * @Template
     * @Route("/cases/bulk/failures")
     * @Method("GET")
     */
    public function failuresAction()
    {
        if (false === $this->get('security.context')->isGranted('bulkCreateCases', $this->getUser())) {
            $this->setSessionParameter('errorMsg', self::NO_PERMISSIONS_ERROR);
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }
        $bulkDocumentError = $this->getSessionParameter('bulkDocumentError');
        if (isset($bulkDocumentError)) {
            $this->setSessionParameter('bulkDocumentError', null);
        }

        $bulkDocumentRepo    = $this->get('home_office_alfresco_api.bulk_document.repository');
        $bulkDocumentFactory = $this->get('home_office_alfresco_api.bulk_document.factory');
        $ctsHelper           = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $bulkDocumentsArray  = $bulkDocumentRepo->getBulkCreateErrors();
        $bulkDocuments       = array();
        foreach ($bulkDocumentsArray as $line) {
            $bulkDocument = $bulkDocumentFactory->build($line);
            array_push($bulkDocuments, $bulkDocument);
        }
        foreach ($bulkDocuments as $bulkDocument) {
            $this->buildDeleteForm($bulkDocument);
        }
        return array(
            'bulkDocuments'     => $bulkDocuments,
            'ctsHelper'         => $ctsHelper,
            'bulkDocumentError' => $bulkDocumentError
        );
    }

    /**
     * @Template
     * @Route("/cases/bulk/download/{documentNodeRef}")
     * @Method("GET")
     */
    public function downloadAction($documentNodeRef)
    {
        $result = $this->downloadDocument($documentNodeRef);
        if (is_bool($result) && !$result) {
            $this->setSessionParameter('errorMsg', self::GENERAL_FILE_DOWNLOAD_ERROR);
            return $this->redirect($this->generateUrl('homeoffice_cts_bulkcase_failures'));
        }
        $nodeId   = $result['nodeId'];
        $fileName = $result['fileName'];
        return $this->createDocumentResponse($nodeId, $fileName);
    }

    /**
     *
     * @Template
     * @Route("/cases/bulk/delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('bulkCreateCases', $this->getUser())) {
            $this->setSessionParameter('errorMsg', self::NO_PERMISSIONS_ERROR);
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }
        $nodeRef          = $request->request->get('form')['id'];
        $bulkDocumentRepo = $this->get('home_office_alfresco_api.bulk_document.repository');
        $response         = $bulkDocumentRepo->delete($nodeRef);
        if (!$response) {
            $this->setSessionParameter('bulkDocumentError', self::DELETE_FILE_ERROR);
        }
        return $this->redirect($this->generateUrl('homeoffice_cts_bulkcase_failures'));
    }

    /**
     *
     * @param BulkDocument $bulkDocument
     */
    private function buildDeleteForm($bulkDocument)
    {
        $deleteForm = $this->createFormBuilder($bulkDocument)
            ->add('id', 'hidden', array('data' => $bulkDocument->getId()))
            ->add('delete', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'upload-delete-btn')))
            ->getForm();
        $bulkDocument->setDeleteForm($deleteForm->createView());
    }
}
