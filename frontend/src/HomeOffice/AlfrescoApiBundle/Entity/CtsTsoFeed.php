<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CtsTsoFeed
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
        return null === $this->name ? null : $this->getUploadRootDir().DIRECTORY_SEPARATOR.'TSOFEED_'.$this->name;
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
            'TSOFEED_'.$this->getFile()->getClientOriginalName()
        );
        $this->name = $this->getFile()->getClientOriginalName();
        $this->file = null;
    }
}
