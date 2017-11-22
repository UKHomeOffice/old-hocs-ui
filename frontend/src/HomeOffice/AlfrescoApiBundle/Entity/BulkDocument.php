<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BulkDocument extends CtsNode
{
 
    /**
     *
     * @var string
     */
    private $name;
 
    /**
     * @var $file
     *
     * @Assert\File(maxSize="10000000")
     * @Assert\NotBlank()
     */
    private $file;
 
    /**
     *
     * @var string
     */
    private $autoCreateFailureMessage;
 
    /**
     *
     * @var string
     */
    private $autoCreateFailureDateTime;
 
    /**
     * @var Form
     */
    private $deleteForm;
 
    /**
     *
     * @param string $workspace
     * @param string $store
     * @param UploadedFile $file
     */
    public function __construct($workspace, $store, $file = null)
    {
        parent::__construct($workspace, $store);
        $this->file = $file;
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
    public function getAutoCreateFailureMessage()
    {
        return $this->autoCreateFailureMessage;
    }

    /**
     *
     * @return string
     */
    public function getAutoCreateFailureDatetime()
    {
        return $this->autoCreateFailureDateTime;
    }

    /**
     *
     * @param string $autoCreateFailureMessage
     */
    public function setAutoCreateFailureMessage($autoCreateFailureMessage)
    {
        $this->autoCreateFailureMessage = $autoCreateFailureMessage;
    }

    /**
     *
     * @param string $autoCreateFailureDatetime
     */
    public function setAutoCreateFailureDatetime($autoCreateFailureDatetime)
    {
        $this->autoCreateFailureDateTime = $autoCreateFailureDatetime;
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
        return null === $this->name ?
            null :
            $this->getUploadRootDir().DIRECTORY_SEPARATOR.'BULK_CREATE_DOCUMENT_'.$this->name;
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
            'BULK_CREATE_DOCUMENT_' . $this->getFile()->getClientOriginalName()
        );
        $this->name = $this->getFile()->getClientOriginalName();
        $this->file = null;
    }
}
