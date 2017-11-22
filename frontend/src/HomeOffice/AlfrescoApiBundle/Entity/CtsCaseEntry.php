<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * @Assert\Callback(methods={"validate"})
 */
class CtsCaseEntry
{
    /**
     * @var boolean
     */
    private $validate = false;
 
    /**
     * @var string $correspondenceType
     *
     * @Assert\NotBlank()
     */
    private $correspondenceType;

    /**
     * @var DateTime $dateReceived
     */
    private $dateReceived;

    /**
     * @var DateTime $opDate
     */
    private $opDate;

    /**
     * @var DateTime $caseResponseDeadline
     */
    private $caseResponseDeadline;
 
    /**
     * @var string $uin
     */
    private $uin;
 
    /**
     * @var file
     */
    private $originalDocument;
 
    /**
     *
     * @var boolean
     */
    private $foiIsEir;
 
    /**
     *
     * @var string
     */
    private $hmpoStage;

    /**
     * @var \DateTime $departureDateFromUK
     */
    protected $departureDateFromUK;
    /**
     * @return string
     */
    
    /**
     * CtsCaseEntry constructor.
     *
     * A little hack to allow the new form to work whilst still allowing the old form to operate as before.  Look to
     * remove this when the old forms are removed.
     */
    public function __construct()
    {
        isset($_POST['correspondenceType']) ? $this->setCorrespondenceType($_POST['correspondenceType']) : null;
    }

    /**
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getOriginalDocument()
    {
        return $this->originalDocument;
    }

    /**
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $originalDocument
     */
    public function setOriginalDocument($originalDocument)
    {
        $this->originalDocument = $originalDocument;
    }

    /**
     * @return string
     */
    public function getCorrespondenceType()
    {
        return $this->correspondenceType;
    }

    /**
     * @param string $correspondenceType
     */
    public function setCorrespondenceType($correspondenceType)
    {
        $this->correspondenceType = $correspondenceType;
    }
    /**
     * @return DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param string $dateReceived
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);
    }

    /**
     * @return string
     */
    public function getUin()
    {
        return $this->uin;
    }

    /**
     * @param string $uin
     */
    public function setUin($uin)
    {
        $this->uin = $uin;
    }

    /**
     * @return DateTime
     */
    public function getOpDate()
    {
        return $this->opDate;
    }

    /**
     * @param string $opDate
     */
    public function setOpDate($opDate)
    {
        $this->opDate = DateHelper::forceDateTimeOrBlank($opDate);
    }

    /**
     * @return DateTime
     */
    public function getCaseResponseDeadline()
    {
        return $this->caseResponseDeadline;
    }

    /**
     * @param string $caseResponseDeadline
     */
    public function setCaseResponseDeadline($caseResponseDeadline)
    {
        $this->caseResponseDeadline = DateHelper::forceDateTimeOrBlank($caseResponseDeadline);
    }
 
    /**
     * @return boolean
     */
    public function getFoiIsEir()
    {
        return $this->foiIsEir == "true" ? true : false;
    }

    /**
     * @param string $foiIsEir
     */
    public function setFoiIsEir($foiIsEir)
    {
        $this->foiIsEir = $foiIsEir;
    }
 
    /**
     *
     * @return string
     */
    public function getHmpoStage()
    {
        return $this->hmpoStage;
    }

    /**
     *
     * @param string $hmpoStage
     */
    public function setHmpoStage($hmpoStage)
    {
        $this->hmpoStage = $hmpoStage;
    }

     
    /**
     *
     * @return boolean
     */
    public function getValidate()
    {
        return $this->validate;
    }
 
    /**
     *
     * @param boolean $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDateFromUK()
    {
        return $this->departureDateFromUK;
    }

    /**
     * @param \DateTime $departureDateFromUK
     */
    public function setDepartureDateFromUK($departureDateFromUK)
    {
        $this->departureDateFromUK =  DateHelper::forceDateTimeOrBlank($departureDateFromUK);
    }

    /**
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->validate) {
            if (in_array(
                $this->correspondenceType,
                ['MIN', 'TRO', 'ICMB', 'IMCM', 'DTEN', 'UTEN', 'FOI', 'COM', 'GEN']
                )
            ) {
                if (!$this->dateReceived instanceof \DateTime) {
                    $context->addViolationAt('dateReceived', 'This value should not be blank.', [], null);
                }
                if ($this->originalDocument === null) {
                    $context->addViolationAt('originalDocument', 'This value should not be blank.', [], null);
                }
            }

            if (in_array($this->correspondenceType, ['DTEN', 'UTEN'])) {
                if (!$this->caseResponseDeadline instanceof \DateTime) {
                    $context->addViolationAt('caseResponseDeadline', 'This value should not be blank.', [], null);
                }
            }

            if (in_array($this->correspondenceType, [ 'COM' ])) {
                if ($this->hmpoStage === null) {
                    $context->addViolationAt('hmpoStage', 'This value should not be blank.', [], null);
                }
            }
            if (in_array($this->correspondenceType, [ 'COL' ])) {
                if (!$this->dateReceived instanceof \DateTime) {
                    $context->addViolationAt('dateReceived', 'This value should not be blank.', [], null);
                }
                if ($this->departureDateFromUK === null) {
                    $context->addViolationAt('departureDateFromUK', 'This value should not be blank.', [], null);
                }
            }

            if (in_array($this->correspondenceType, ['NPQ', 'LPQ', 'OPQ'])) {
                if (!$this->opDate instanceof \DateTime) {
                    $context->addViolationAt('opDate', 'This value should not be blank.', [], null);
                }
                if (!$this->caseResponseDeadline instanceof \DateTime) {
                    $context->addViolationAt('caseResponseDeadline', 'This value should not be blank.', [], null);
                }
                if (empty($this->uin)) {
                    $context->addViolationAt('uin', 'This value should not be blank.', [], null);
                }
            }
        }
    }
 
    public function toArray()
    {
        return (get_object_vars($this));
    }
}
