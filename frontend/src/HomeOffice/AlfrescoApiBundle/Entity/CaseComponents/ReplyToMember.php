<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

/**
 * Class ReplyToMember
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\CaseComponents
 */
trait ReplyToMember
{
    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Reply"}, message="Select a member to reply to")
     */
    protected $member;

    /**
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param string $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }
}
