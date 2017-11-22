<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CtsCaseDocument
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class CtsCaseDocument extends CtsNode
{
    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Default", "Case_Document"}, message="You must select a document type")
     * 
     * @todo Default group can be removed once Guft is live
     */
    private $documentType;

    /**
     * @var string
     */
    private $documentDescription;

    /**
     * @var string
     */
    private $createdDate;

    /**
     * @var string
     */
    private $lastModificationDate;

    /**
     * @var string
     */
    private $versionNumber;

    /**
     *
     * @var string
     */
    private $name;

    /**
     * @var $file
     *
     * @Assert\File(groups={"Default", "Case_Document"}, maxSize="10000000")
     * @Assert\NotBlank(groups={"Default", "Case_Document"}, message="You must select a document to upload")
     * 
     * @todo Default group can be removed once Guft is live
     */
    private $file;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $createdBy;

    /**
     *
     * @var string
     */
    private $fileVersionUrl;

    /**
     * @var Form
     */
    private $addVersionForm;

    /**
     * @var Form
     */
    private $deleteForm;

    /**
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        parent::__construct($workspace, $store);
    }

    /**
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     *
     * @param string $documentType
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     *
     * @return string
     */
    public function getDocumentDescription()
    {
        return $this->documentDescription;
    }

    /**
     *
     * @param string $documentDescription
     */
    public function setDocumentDescription($documentDescription)
    {
        $this->documentDescription = $documentDescription;
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
        $this->createdDate = $createdDate;
    }

    /**
     *
     * @return string
     */
    public function getLastModificationDate()
    {
        return $this->lastModificationDate;
    }

    /**
     *
     * @param string $lastModificationDate
     */
    public function setLastModificationDate($lastModificationDate)
    {
        $this->lastModificationDate = $lastModificationDate;
    }

    /**
     *
     * @return string
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }

    /**
     *
     * @param string $versionNumber
     */
    public function setVersionNumber($versionNumber)
    {
        $this->versionNumber = $versionNumber;
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
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     *
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     *
     * @return string
     */
    public function getFileVersionUrl()
    {
        return $this->fileVersionUrl;
    }

    /**
     *
     * @param string $fileVersionUrl
     */
    public function setFileVersionUrl($fileVersionUrl)
    {
        $this->fileVersionUrl = $fileVersionUrl;
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
    public function getWebPath($caseNodeRef)
    {
        return null === $this->name ? null : $this->getUploadRootDir().DIRECTORY_SEPARATOR.$caseNodeRef.'_'.$this->name;
    }

    /**
     *
     * @return void
     */
    public function upload($caseNodeRef)
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $caseNodeRef . '_' . $this->getFile()->getClientOriginalName()
        );
        $this->name = $this->getFile()->getClientOriginalName();
        $this->file = null;
    }

    /**
     *
     * @param form $addVersionForm
     */
    public function setAddVersionForm($addVersionForm)
    {
        $this->addVersionForm = $addVersionForm;
    }

    /**
     * return form
     */
    public function getAddVersionForm()
    {
        return $this->addVersionForm;
    }

    /**
     *
     * @param form $deleteForm
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
     * Used for document preview to check if the file should be downloaded
     * directly or if a transformation should be requested.
     * @return boolean
     */
    public function canDownloadDirect()
    {
        $directDownloadMimeTypes = [
            'application/pdf',
            'image/gif',
            'image/png',
            'image/jpeg'
        ];
        return in_array($this->mimeType, $directDownloadMimeTypes);
    }
}
