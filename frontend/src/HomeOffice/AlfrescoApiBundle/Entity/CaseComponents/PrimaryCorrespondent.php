<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

/**
 * Class PrimaryCorrespondent
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\CaseComponents
 */
trait PrimaryCorrespondent
{
    use CorrespondentDetails;

    /**
     * @var string
     */
    private $typeOfCorrespondent;

    /**
     * @var string
     */
    private $typeOfRepresentative;

    /**
     * @var boolean
     */
    private $consentAttached;

    /**
     * @var boolean
     */
    private $replyToCorrespondent;

    /**
     * Get TypeOfCorrespondent
     *
     * @return string
     */
    public function getTypeOfCorrespondent()
    {
        return $this->typeOfCorrespondent;
    }

    /**
     * Set TypeOfCorrespondent
     *
     * @param string $typeOfCorrespondent
     *
     * @return $this
     */
    public function setTypeOfCorrespondent($typeOfCorrespondent)
    {
        $this->typeOfCorrespondent = $typeOfCorrespondent;

        return $this;
    }

    /**
     * Get TypeOfRepresentative
     *
     * @return mixed
     */
    public function getTypeOfRepresentative()
    {
        return $this->typeOfRepresentative;
    }

    /**
     * Set TypeOfRepresentative
     *
     * @param mixed $typeOfRepresentative
     *
     * @return $this
     */
    public function setTypeOfRepresentative($typeOfRepresentative)
    {
        $this->typeOfRepresentative = $typeOfRepresentative;

        return $this;
    }

    /**
     * Get ConsentAttached
     *
     * @return bool
     */
    public function isConsentAttached()
    {
        return $this->consentAttached;
    }

    /**
     * Set ConsentAttached
     *
     * @param bool $consentAttached
     *
     * @return $this
     */
    public function setConsentAttached($consentAttached)
    {
        $this->consentAttached = $consentAttached;

        return $this;
    }

    /**
     * Get ReplyToCorrespondent
     *
     * @return bool
     */
    public function getReplyToCorrespondent()
    {
        return $this->replyToCorrespondent;
    }

    /**
     * Set ReplyToCorrespondent
     *
     * @param bool $replyToCorrespondent
     *
     * @return $this
     */
    public function setReplyToCorrespondent($replyToCorrespondent)
    {
        $this->replyToCorrespondent = $replyToCorrespondent;

        return $this;
    }
}