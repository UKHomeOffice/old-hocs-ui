<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Assert\Callback(methods={"validateFile"})
 */
class CtsCaseDocumentTemplate extends CtsNode
{
    /**
     * @var string
     * @Assert\NotBlank(message="Case type must be populated.")
     */
    private $appliesToCorrespondenceType;

    /**
     * @var string
     * @Assert\NotBlank(message="Template name must be populated.")
     */
    private $templateName;
 
    /**
     * @var string
     */
    private $createdDate;
 
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Valid from date type must be populated.")
     */
    private $validFromDate;
 
    /**
     * @var string
     * @Assert\NotBlank(message="Valid to date type must be populated.")
     */
    private $validToDate;
 
    /**
     *
     * @var string
     */
    private $name;
 
    /**
     * @var $file
     *
     * @Assert\File(
     *      maxSize="10000000",
     *      mimeTypes = {"application/vnd.oasis.opendocument.text", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     *      mimeTypesMessage = "Please upload a valid .docx or .odt file"
     * )
     */
    private $file;
 
    /**
     * @var string
     */
    private $mimeType;
 
    /**
     * @var Form
     */
    private $deleteForm;
 
    /**
     *
     * @var boolean
     */
    private $validateFile;
 
    /**
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store, $validateFile = true)
    {
        parent::__construct($workspace, $store);
        $this->validateFile = $validateFile;
    }
 
    /**
     *
     * @return string
     */
    public function getAppliesToCorrespondenceType()
    {
        return $this->appliesToCorrespondenceType;
    }

    /**
     *
     * @param string $getAppliesToCorrespondenceType
     */
    public function setAppliesToCorrespondenceType($getAppliesToCorrespondenceType)
    {
        $this->appliesToCorrespondenceType = $getAppliesToCorrespondenceType;
    }
 
    /**
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     *
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }
 
    /**
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     *
     * @param string $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = DateHelper::forceDateTimeOrBlank($createdDate);
    }
 
    /**
     *
     * @return string
     */
    public function getValidFromDate()
    {
        return $this->validFromDate;
    }

    /**
     *
     * @param string $validFromDate
     */
    public function setValidFromDate($validFromDate)
    {
        $this->validFromDate = DateHelper::forceDateTimeOrBlank($validFromDate);
    }
 
    /**
     *
     * @return string
     */
    public function getValidToDate()
    {
        return $this->validToDate;
    }

    /**
     *
     * @param string $validToDate
     */
    public function setValidToDate($validToDate)
    {
        $this->validToDate = DateHelper::forceDateTimeOrBlank($validToDate);
    }
     
    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
 
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
 
    /**
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return bool
     */
    public function isWordDoc()
    {
        return $this->getMimeType() === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    }
 
    /**
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }
 
    /**
     *
     * @return boolean
     */
    public function getValidateFile()
    {
        return $this->validateFile;
    }
 
    /**
     *
     * @param boolean $validateFile
     */
    public function setValidateFile($validateFile)
    {
        $this->validateFile = $validateFile;
    }
     
    /**
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return '/tmp';
    }
 
    /**
     *
     * @return string
     */
    public function getWebPath()
    {
        //@codingStandardsIgnoreStart
        return null === $this->name ? null : $this->getUploadRootDir().DIRECTORY_SEPARATOR."DOCUMENT_TEMPLATE_".$this->name;
        //@codingStandardsIgnoreEnd
    }
 
    /**
     *
     * @return void
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move(
            $this->getUploadRootDir(),
            "DOCUMENT_TEMPLATE_" . $this->getFile()->getClientOriginalName()
        );
        $this->name = $this->getFile()->getClientOriginalName();
        $this->file = null;
    }

    /**
     *
     * @param type $deleteForm
     */
    public function setDeleteForm($deleteForm)
    {
        $this->deleteForm = $deleteForm;
    }
 
    /**
     * return form
     */
    public function getDeleteForm()
    {
        return $this->deleteForm;
    }
 
    /**
     *
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
     */
    public function validateFile(ExecutionContextInterface $context)
    {
        if ($this->validateFile && $this->file == null) {
            $context->addViolationAt('file', 'Template file must be populated.', array(), null);
        }
    }

    /**
     * When downloading a populated case document we can use the case and tags to append values to the filename
     *
     * @param array        $tags
     * @param CtsCase|null $case
     *
     * @return string
     */
    public function getDownloadFilename(array $tags = [], CtsCase $case = null)
    {
        $filename = $this->getName();

        if ($case instanceof CtsCase) {
            foreach ($tags as $tag) {
                $method = 'get' . ucwords($tag);
                if (method_exists($case, $method)) {
                    $filename = preg_replace('/(\.docx)/', '.' . $case->$method() . '$1', $this->getName());
                }
            }
        }

        return $filename;
    }
}
