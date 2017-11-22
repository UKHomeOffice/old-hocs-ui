<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

trait ReplyToNumberTenCopy
{
    /**
     * @var boolean
     */
    private $replyToNumberTenCopy;
 
    /**
     * @return boolean
     */
    public function getReplyToNumberTenCopy()
    {
        return $this->replyToNumberTenCopy == "true" ? true : false;
    }

    /**
     * @param boolean $replyToNumberTenCopy
     */
    public function setReplyToNumberTenCopy($replyToNumberTenCopy)
    {
        $this->replyToNumberTenCopy = $replyToNumberTenCopy;
    }
}
