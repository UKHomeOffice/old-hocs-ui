<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentTemplateRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

trait DocumentHelper
{
    /**
     * Generic method to retrieve and save a file from alfresco.
     * @param string $documentNodeRef
     * @return boolean
     */
    public function downloadDocument($documentNodeRef)
    {
        $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
        $ctsCaseDocument = $ctsCaseDocumentRepository->getDocumentFile($documentNodeRef);
        if (!$ctsCaseDocument) {
            return false;
        }
        $nodeId = $ctsCaseDocument->getNodeId();
        $fileName = $ctsCaseDocument->getName();
        return array('nodeId' => $nodeId, 'fileName' => $fileName);
    }

    public function downloadDocumentPreview($documentNodeRef, $type)
    {
        /** @var CtsCaseDocumentRepository $ctsCaseDocumentRepository */
        $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
        $ctsCaseDocument = null;
        if ($type == 'PDF') {
            $ctsCaseDocument = $ctsCaseDocumentRepository->getDocumentPdf($documentNodeRef);
        }
        if ($type == 'image') {
            $ctsCaseDocument = $ctsCaseDocumentRepository->getDocumentImg($documentNodeRef);
        }
        if ($ctsCaseDocument) {
            $nodeId = $ctsCaseDocument->getNodeId();
            $fileName = $ctsCaseDocument->getName();
            return array('nodeId' => $nodeId, 'fileName' => $fileName);
        } else {
            return false;
        }
    }
 
    /**
     * Method to retrieve and save a populated template from alfresco.
     * @param string $documentNodeRef
     * @param string $caseNodeRef
     * @return boolean
     */
    public function downloadPopulatedTemplate($documentNodeRef, $caseNodeRef)
    {
        /** @var CtsCaseDocumentTemplateRepository $documentTemplateRepo */
        $documentTemplateRepo = $this->get('home_office_alfresco_api.cts_case_document_template.repository');

        $documentTemplate = $documentTemplateRepo->getDocumentTemplateFile($documentNodeRef, $caseNodeRef);
        if (!$documentTemplate) {
            return false;
        }

        return [
            'nodeId'   => $documentTemplate->getNodeId(),
            'fileName' => $documentTemplate->getName()
        ];
    }
 
    /**
     * Method to create the response for a failed preview document
     *
     * @return BinaryFileResponse
     */
    public function createDocumentPreviewFailedResponse()
    {
        $path = __DIR__ . '/../../../../web';
        $fileName = 'no-preview-available.pdf';

        $response = new BinaryFileResponse("$path/files/no-preview-available.pdf");
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName,
            iconv('UTF-8', 'ASCII//TRANSLIT', $fileName)
        );

        return $response;
    }
 
    /**
     * Generic method to create the response for a downloaded document.
     *
     * @param string $nodeId
     * @param string $fileName
     * @param string $disposition
     *
     * @return BinaryFileResponse
     */
    public function createDocumentResponse($nodeId, $fileName, $disposition = ResponseHeaderBag::DISPOSITION_INLINE)
    {
        $response = new BinaryFileResponse('/tmp/'.$nodeId);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            $disposition,
            $fileName,
            iconv('UTF-8', 'ASCII//TRANSLIT', $fileName)
        );

        return $response;
    }
}
