<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class CtsCaseStandardLine
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity
 *
 * @Assert\Callback(methods={"validateFile"})
 */
class CtsCaseStandardLine extends CtsNode
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Name must be populated")
     */
    private $name;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @var string
     */
    private $associatedTopic;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Associated unit must be populated")
     */
    private $associatedUnit;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Review date must be populated")
     */
    private $reviewDate;

    /**
     * @var string
     */
    private $createdDate;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="10000000")
     */
    private $file;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $extension;

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get OriginalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set OriginalName
     *
     * @param string $originalName
     *
     * @return $this
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }


    /**
     * Get AssociatedTopic
     *
     * @return string
     */
    public function getAssociatedTopic()
    {
        return $this->associatedTopic;
    }

    /**
     * Set AssociatedTopic
     *
     * @param string $associatedTopic
     *
     * @return $this
     */
    public function setAssociatedTopic($associatedTopic)
    {
        $this->associatedTopic = $associatedTopic;

        return $this;
    }

    /**
     * Get AssociatedUnit
     *
     * @return string
     */
    public function getAssociatedUnit()
    {
        return $this->associatedUnit;
    }

    /**
     * Set AssociatedUnit
     *
     * @param string $associatedUnit
     *
     * @return $this
     */
    public function setAssociatedUnit($associatedUnit)
    {
        $this->associatedUnit = $associatedUnit;

        return $this;
    }

    /**
     * Get ReviewDate
     *
     * @return string
     */
    public function getReviewDate()
    {
        return $this->reviewDate;
    }

    /**
     * Set ReviewDate
     *
     * @param string
     *
     * @return $this
     */
    public function setReviewDate($reviewDate)
    {
        $this->reviewDate = DateHelper::forceDateTimeOrBlank($reviewDate);

        return $this;
    }

    /**
     * Is Review Required?
     *
     * @return bool
     */
    public function isReviewRequired()
    {
        return $this->reviewDate <= new \DateTime();
    }
 
    /**
     * Get CreatedDate
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set CreatedDate
     *
     * @param string $createdDate
     *
     * @return $this
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = DateHelper::forceDateTimeOrBlank($createdDate);

        return $this;
    }

    /**
     * Is New?
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->getCreatedDate() ? false : true;
    }
 
    /**
     * Set File
     *
     * @param UploadedFile $file
     *
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get File
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Validate File
     *
     * @param ExecutionContextInterface $context
     */
    public function validateFile(ExecutionContextInterface $context)
    {
        if ($this->isNew() && $this->file == null) {
            $context->buildViolation('You must select a document to upload')->atPath('file')->addViolation();
        }
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
     * Set MimeType
     *
     * @param string $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get Extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set Extension
     *
     * @param string $extension
     *
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get FileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getName() . ($this->getExtension() ? '.'.$this->getExtension(): null);
    }
 
    /**
     * Upload
     *
     * @return $this
     */
    public function upload()
    {
        if ($this->getFile() instanceof UploadedFile) {
            $this->setMimeType($this->getFile()->getMimeType());
            $this->getFile()->move($this->getUploadRootDir(), $this->getUploadFileName());
        }

        return $this;
    }

    /**
     * Get WebPath
     *
     * @return string|null
     */
    public function getWebPath()
    {
        return $this->name ? $this->getUploadRootDir().'/'.$this->getUploadFileName() : null;
    }

    /**
     * Get Upload Root Directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return '/tmp';
    }

    /**
     * Get Upload File Name
     *
     * @return string
     */
    protected function getUploadFileName()
    {
        return 'STANDARD_LINE_'.$this->getName();
    }


}
