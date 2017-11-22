<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

trait HomeSecretaryReply
{
 
    /**
     * @var boolean
     */
    protected $homeSecretaryReply;
 
    /**
     * @return boolean
     */
    public function getHomeSecretaryReply()
    {
        return $this->homeSecretaryReply == "true" ? true : false;
    }

    /**
     * @param boolean $homeSecretaryReply
     */
    public function setHomeSecretaryReply($homeSecretaryReply)
    {
        $this->homeSecretaryReply = $homeSecretaryReply;
    }
}
