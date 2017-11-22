<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

use Symfony\Component\Validator\Constraints as Assert;

trait ReplyToMemberDetails
{

    /**
     * @var string
     */
    protected $replyToName;

    /**
     * @var string
     */
    protected $replyToAddressLine1;

    /**
     * @var string
     */
    protected $replyToAddressLine2;

    /**
     * @var string
     */
    protected $replyToAddressLine3;

    /**
     * @var string
     */
    protected $replyToPostcode;

    /**
     * @var string
     */
    protected $replyToCountry;

    /**
     * @var string
     */
    protected $replyToTelephone;

    /**
     * @Assert\Email(
     *     message = "You must enter a valid email address",
     * )
     *
     * @var string
     */
    protected $replyToEmail;
 
    /**
     * @return string
     */
    public function getReplyToEmail()
    {
        return $this->replyToEmail;
    }

    /**
     * @param string $replyToEmail
     */
    public function setReplyToEmail($replyToEmail)
    {
        $this->replyToEmail = $replyToEmail;
    }

    /**
     * @return string
     */
    public function getReplyToTelephone()
    {
        return $this->replyToTelephone;
    }

    /**
     * @param string $replyToTelephone
     */
    public function setReplyToTelephone($replyToTelephone)
    {
        $this->replyToTelephone = $replyToTelephone;
    }

    /**
     * @return string
     */
    public function getReplyToCountry()
    {
        return $this->replyToCountry;
    }

    /**
     * @param string $replyToCountry
     */
    public function setReplyToCountry($replyToCountry)
    {
        $this->replyToCountry = $replyToCountry;
    }

    /**
     * @return string
     */
    public function getReplyToAddressLine1()
    {
        return $this->replyToAddressLine1;
    }

    /**
     * @param string $replyToAddressLine1
     */
    public function setReplyToAddressLine1($replyToAddressLine1)
    {
        $this->replyToAddressLine1 = $replyToAddressLine1;
    }

    /**
     * @return string
     */
    public function getReplyToAddressLine2()
    {
        return $this->replyToAddressLine2;
    }

    /**
     * @param string $replyToAddressLine2
     */
    public function setReplyToAddressLine2($replyToAddressLine2)
    {
        $this->replyToAddressLine2 = $replyToAddressLine2;
    }

    /**
     * @return string
     */
    public function getReplyToAddressLine3()
    {
        return $this->replyToAddressLine3;
    }

    /**
     * @param string $replyToAddressLine3
     */
    public function setReplyToAddressLine3($replyToAddressLine3)
    {
        $this->replyToAddressLine3 = $replyToAddressLine3;
    }

    /**
     * Get Reply To Address
     *
     * @param string $delimiter
     *
     * @return string
     */
    public function getReplyToAddress($delimiter = ', ')
    {
        return
            ($this->getReplyToAddressLine1() ? $this->getReplyToAddressLine1() . $delimiter : null).
            ($this->getReplyToAddressLine2() ? $this->getReplyToAddressLine2() . $delimiter : null).
            ($this->getReplyToAddressLine3() ? $this->getReplyToAddressLine3() . $delimiter : null).
            ($this->getReplyToPostcode() ?: null);
    }

    /**
     * @return string
     */
    public function getReplyToName()
    {
        return $this->replyToName;
    }

    /**
     * @param string $replyToName
     * @return object CtsCase
     */
    public function setReplyToName($replyToName)
    {
        $this->replyToName = $replyToName;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplyToPostcode()
    {
        return $this->replyToPostcode;
    }

    /**
     * @param string $ReplyToPostcode
     */
    public function setReplyToPostcode($ReplyToPostcode)
    {
        $this->replyToPostcode = $ReplyToPostcode;
    }
}
