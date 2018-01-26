<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentTemplateFactory;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseStandardLineFactory;
use HomeOffice\AlfrescoApiBundle\Repository\BulkDocumentRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentTemplateRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseMinuteRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseStandardLineRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsWorkflowRepository;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\CtsBundle\Form\AjaxResponseBuilder;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use HomeOffice\AlfrescoApiBundle\Service\Paginator;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CtsController extends Controller
{
    /**
     * Perform validation on an entity, return false if valid or an array or
     * errors if not.
     * @param object $entity
     * @return array | boolean
     */
    protected function validate($entity)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($entity);
        return count($errors) == 0 ? false : $errors;
    }

    /**
     * Set a value in the session.
     * @param string $name
     * @param mixed $value
     */
    protected function setSessionParameter($name, $value)
    {
        $this->getRequest()->getSession()->set($name, $value);
    }

    /**
     * Retrieve a value from the session.
     * @param string $name
     * @return mixed
     */
    protected function getSessionParameter($name)
    {
        return $this->getRequest()->getSession()->get($name);
    }
 
    /**
     *
     * @param string $correspondenceType
     * @param boolean $restrictToValidToday
     * @return array
     */
    protected function getDocumentTemplates($correspondenceType = null, $restrictToValidToday = true)
    {
        // @codingStandardsIgnoreStart
        $ctsCaseDocumentTemplateRepository = $this->get('home_office_alfresco_api.cts_case_document_template.repository');
        // @codingStandardsIgnoreEnd
        $ctsCaseDocumentTemplateFactory = $this->get('home_office_alfresco_api.cts_case_document_template.factory');
        $documentTemplatesArray = $ctsCaseDocumentTemplateRepository->getDocumentTemplates(
            $correspondenceType,
            $restrictToValidToday
        );
        $documentTemplates = array();
        foreach ($documentTemplatesArray as $documentTemplate) {
            array_push($documentTemplates, $ctsCaseDocumentTemplateFactory->build($documentTemplate));
        }
        return $documentTemplates;
    }
 
    /**
     * @param ParameterBag $queryParameters
     * @param string       $pagePath
     *
     * @return Paginator
     */
    protected function setupPaginator($queryParameters, $pagePath)
    {
        $paginator = new Paginator();
        $paginator->setPageNumber((int) $queryParameters->get('pageNumber'));
        $paginator->setPagePath($pagePath);

        return $paginator;
    }
 
    /**
     *
     * @param int $pageNumber
     * @param string $pagePath
     * @return Paginator
     */
    protected function setupPaginatorDirect($pageNumber, $pagePath)
    {
        $paginator = new Paginator();
        $paginator->setPageNumber($pageNumber);
        $paginator->setPagePath($pagePath);
        return $paginator;
    }

    /**
     * Manually add errors to CtsCaseMinuteType form type.
     *
     * @param CtsCaseMinute $ctsCaseMinute
     * @param Form          $ctsCaseMinuteForm
     */
    protected function populateCaseMinuteFormErrors(CtsCaseMinute $ctsCaseMinute, $ctsCaseMinuteForm)
    {
        if ($ctsCaseMinute->getMinuteContent() == null) {
            $error = new FormError('This value must be entered.');
            $ctsCaseMinuteForm->get('minuteContent')->addError($error);
        }
    }
 
    /**
     * Manually add errors to CtsCaseDocumentType form type.
     *
     * @param CtsCaseDocument $ctsCaseDocument
     * @param Form            $ctsCaseDocumentForm
     */
    protected function populateCaseDocumentFormErrors(CtsCaseDocument $ctsCaseDocument, $ctsCaseDocumentForm)
    {
        if ($ctsCaseDocument->getDocumentType() == null) {
            $error = new FormError('This value must be entered.');
            $ctsCaseDocumentForm->get('documentType')->addError($error);
        }
        if ($ctsCaseDocument->getDocumentDescription() == null) {
            $error = new FormError('This value must be entered.');
            $ctsCaseDocumentForm->get('documentDescription')->addError($error);
        }
        if ($ctsCaseDocument->getName() == null) {
            $error = new FormError('A file must be selected.');
            $ctsCaseDocumentForm->get('file')->addError($error);
        }
    }
 
    /**
     * Get a value from the session
     * @param string $name
     * @return mixed
     */
    protected function getAndClearValueInSession($name)
    {
        $msg = $this->getSessionParameter($name);
        if (isset($msg)) {
            $this->setSessionParameter($name, null);
        }
        return $msg;
    }
 
    /**
     * @param string $exportResult
     *
     * @return BinaryFileResponse
     */
    protected function generateExportFileResponse($exportResult)
    {
        $fileName = $exportResult . '.xls';
        $response = new BinaryFileResponse("/tmp/$exportResult");
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName,
            iconv('UTF-8', 'ASCII//TRANSLIT', $fileName)
        );

        return $response;
    }

    /**
     * @param string $nodeRef
     * @param $audit
     * @param bool $setOwner
     * @return CtsCase
     */
    protected function getCase($nodeRef, $audit = "", $setOwner = true)
    {
        $case = $this->getCtsCaseRepository()->getCase($nodeRef, $audit);
        if ($setOwner === true) {
            $this->getCtsHelper()->setCaseOwner($case);
        }

        return $case;
    }

    /**
     * @return CtsCaseRepository
     */
    protected function getCtsCaseRepository()
    {
        return $this->get('home_office_alfresco_api.cts_case.repository');
    }

    /**
     * @return CtsCaseDocumentRepository
     */
    protected function getCtsCaseDocumentRepository()
    {
        return $this->get('home_office_alfresco_api.cts_case_document.repository');
    }

    /**
     * @return BulkDocumentRepository
     */
    protected function getBulkDocumentRepository()
    {
        return $this->get('home_office_alfresco_api.bulk_document.repository');
    }

    /**
     * @return CtsCaseDocumentTemplateRepository
     */
    protected function getCtsCaseDocumentTemplateRepository()
    {
        return $this->get('home_office_alfresco_api.cts_case_document_template.repository');
    }

    /**
     * @return CtsCaseDocumentTemplateFactory
     */
    protected function getCtsCaseDocumentTemplateFactory()
    {
        return $this->get('home_office_alfresco_api.cts_case_document_template.factory');
    }

    /**
     * @return CtsCaseStandardLineRepository
     */
    protected function getStandardLineRepository()
    {
        return $this->get('home_office_alfresco_api.cts_case_standard_line.repository');
    }

    /**
     * @return CtsCaseStandardLineFactory
     */
    protected function getStandardLineFactory()
    {
        return $this->get('home_office_alfresco_api.cts_case_standard_line.factory');
    }

    /**
     * @return CtsListsRepository
     */
    protected function getCtsListsRepository()
    {
        return $this->get('home_office_alfresco_api.cts_lists.repository');
    }

    /**
     * @return CtsWorkflowRepository
     */
    protected function getCtsWorkflowRepository()
    {
        return $this->get('home_office_alfresco_api.cts_workflow.repository');
    }

    /**
     * @return CtsCaseMinuteRepository
     */
    protected function getCtsCaseMinuteRepository()
    {
        return $this->get('home_office_alfresco_api.cts_case_minute.repository');
    }

    /**
     * @return CTSHelper
     */
    protected function getCtsHelper()
    {
        return $this->get('home_office_alfresco_api.cts_case.cts_helper');
    }

    /**
     * @return CaseProgressHelper
     */
    protected function getCaseProgressHelper()
    {
        return $this->get('home_office_alfresco_api.cts_case.progress_helper');
    }

    /**
     * @return ListHandler
     */
    protected function getListHandler()
    {
        return $this->get('home_office_list.handler');
    }

    /**
     * Create Ajax Form Response
     *
     * @param Form $form
     *
     * @return AjaxResponseBuilder
     */
    protected function createAjaxFormResponse(Form $form)
    {
        /** @var AjaxResponseBuilder $ajaxFormResponse */
        $ajaxFormResponse = $this->get('home_office_cts.form.ajax_response_builder');
        $ajaxFormResponse->setForm($form);

        return $ajaxFormResponse;
    }
}
