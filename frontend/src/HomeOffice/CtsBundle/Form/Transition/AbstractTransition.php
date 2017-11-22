<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Exception\SavePermissionException;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseDocumentTemplateRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseMinuteRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsWorkflowRepository;
use HomeOffice\AlfrescoApiBundle\Repository\PersonRepository;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\CtsBundle\Form\AjaxResponseBuilder;
use HomeOffice\CtsBundle\Form\Validator\CaseValidator;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AbstractTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
abstract class AbstractTransition
{
    /**
     * @var CtsCaseRepository
     */
    private $caseRepository;

    /**
     * @var CtsWorkflowRepository
     */
    private $workflowRepository;

    /**
     * @var CtsCaseDocumentRepository
     */
    private $documentRepository;

    /**
     * @var CtsCaseMinuteRepository
     */
    private $minuteRepository;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @var CtsCaseDocumentTemplateRepository
     */
    private $documentTemplateRepository;

    /**
     * @var CTSHelper
     */
    private $ctsHelper;

    /**
     * @var AjaxResponseBuilder
     */
    private $ajaxResponseBuilder;

    /**
     * @var CtsCase
     */
    private $case;

    /**
     * @var CaseValidator
     */
    private $validator;

    /**
     * @var Person
     */
    private $user;

    /**
     * Constructor
     *
     * @param CtsCaseRepository                 $caseRepository
     * @param CtsWorkflowRepository             $workflowRepository
     * @param CtsCaseDocumentRepository         $documentRepository
     * @param CtsCaseMinuteRepository           $minuteRepository
     * @param PersonRepository                  $personRepository
     * @param CtsCaseDocumentTemplateRepository $documentTemplateRepository
     * @param CTSHelper                         $ctsHelper
     * @param CaseValidator                     $caseValidator
     * @param Person                            $user
     */
    public function __construct(
        CtsCaseRepository $caseRepository,
        CtsWorkflowRepository $workflowRepository,
        CtsCaseDocumentRepository $documentRepository,
        CtsCaseMinuteRepository $minuteRepository,
        PersonRepository $personRepository,
        CtsCaseDocumentTemplateRepository $documentTemplateRepository,
        CTSHelper $ctsHelper,
        CaseValidator $caseValidator,
        Person $user
    ) {
        $this->caseRepository = $caseRepository;
        $this->workflowRepository = $workflowRepository;
        $this->documentRepository = $documentRepository;
        $this->minuteRepository = $minuteRepository;
        $this->personRepository = $personRepository;
        $this->documentTemplateRepository = $documentTemplateRepository;
        $this->ctsHelper = $ctsHelper;
        $this->validator = $caseValidator;
        $this->user = $user;
    }

    /**
     * @param AjaxResponseBuilder $ajaxResponseBuilder
     * @param Form $form
     * @param CtsCase $case
     *
     * @return static
     */
    public function prepare(AjaxResponseBuilder $ajaxResponseBuilder, Form $form, CtsCase $case)
    {
        $this->ajaxResponseBuilder = $ajaxResponseBuilder;
        $this->ajaxResponseBuilder->setForm($form);
        $this->case = $case;

        return $this;
    }

    /**
     * Get CaseRepository
     *
     * @return CtsCaseRepository
     */
    protected function getCaseRepository()
    {
        return $this->caseRepository;
    }

    /**
     * Get WorkflowRepository
     *
     * @return CtsWorkflowRepository
     */
    protected function getWorkflowRepository()
    {
        return $this->workflowRepository;
    }

    /**
     * Get DocumentRepository
     *
     * @return CtsCaseDocumentRepository
     */
    protected function getDocumentRepository()
    {
        return $this->documentRepository;
    }

    /**
     * Get MinuteRepository
     *
     * @return CtsCaseMinuteRepository
     */
    protected function getMinuteRepository()
    {
        return $this->minuteRepository;
    }

    /**
     * Get PersonRepository
     *
     * @return PersonRepository
     */
    protected function getPersonRepository()
    {
        return $this->personRepository;
    }

    /**
     * Get CtsCaseDocumentTemplateRepository
     *
     * @return CtsCaseDocumentTemplateRepository
     */
    protected function getDocumentTemplateRepository()
    {
        return $this->documentTemplateRepository;
    }

    /**
     * Get CtsHelper
     *
     * @return CTSHelper
     */
    protected function getCtsHelper()
    {
        return $this->ctsHelper;
    }

    /**
     * Get User
     *
     * @return Person
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * Get AjaxResponseBuilder
     *
     * @return AjaxResponseBuilder
     */
    public function getAjaxResponseBuilder()
    {
        return $this->ajaxResponseBuilder;
    }

    /**
     * Get Form
     *
     * @return Form
     */
    protected function getForm()
    {
        return $this->ajaxResponseBuilder->getForm();
    }

    /**
     * Get Button Name
     *
     * @return string
     */
    protected function getButtonName()
    {
        return $this->getForm()->getClickedButton()->getName();
    }

    /**
     * Get CtsCase
     *
     * @return CtsCase
     */
    protected function getCase()
    {
        return $this->case;
    }

    /**
     * Get Response
     *
     * @return JsonResponse
     */
    public function getResponse()
    {
        return $this->ajaxResponseBuilder->createResponse();
    }

    /**
     * @return static
     */
    public function validate()
    {
        $this->validator->validate($this->getForm());

        return $this;
    }

    /**
     * Handle
     */
    public function handle()
    {
        try {
            $this->handleAllocation();
            $this->transition();
        } catch(SavePermissionException $e) {
            $this->getForm()->addError(new FormError($e->getMessage()));
        }
    }

    /**
     * Save Case
     *
     * @return bool
     */
    protected function saveCase()
    {
        return $this->getCaseRepository()->update($this->getCase());
    }

    /**
     * Update Workflow
     *
     * @param string|null $buttonName
     *
     * @return bool
     */
    protected function updateWorkflow($buttonName = null)
    {
        return $this->getWorkflowRepository()->updateWorkflow($this->getCase(),$buttonName ?: $this->getButtonName());
    }

    /**
     * @param string      $field
     * @param string|null $data
     *
     * @return bool
     */
    protected function saveMinuteFromField($field, $data = null)
    {
        if ($this->getForm()->has($field) === false) {
            return false;
        }

        $data = $data ?: $this->getForm()->get($field)->getData();

        try {
            return $this->getMinuteRepository()->create(
                (new CtsCaseMinute())->setMinuteContent($data),
                $this->getCase()->getNodeId()
            );
        } catch (\Exception $e) {
            // @todo unhandled Exception
        }

        return false;
    }

    protected function handleAllocation()
    {
        $form = $this->getForm()->has('allocate') ? $this->getForm()->get('allocate') : $this->getForm();

        if ($form->has('allocateTo') && $form->get('allocateTo')->getData() == 1) {
            $this->getCase()->setAssignedUser($this->getUser()->getUserName());
        }
    }

    /**
     * Transition
     */
    abstract protected function transition();
}
