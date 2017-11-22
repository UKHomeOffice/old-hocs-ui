<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use HomeOffice\AlfrescoApiBundle\Entity\Member;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\PQHouse;
use HomeOffice\ListBundle\Service\ListHandler;

/**
 * Class CaseMemberList
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CaseMemberList
{
    /**
     * @var CTSHelper
     */
    private $ctsHelper;

    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * Constructor
     *
     * @param string      $workspace
     * @param string      $store
     * @param CTSHelper   $ctsHelper
     * @param ListHandler $listHandler
     */
    public function __construct($workspace, $store, CTSHelper $ctsHelper, ListHandler $listHandler)
    {
        $this->workspace = $workspace;
        $this->store = $store;

        $this->ctsHelper = $ctsHelper;
        $this->listHandler = $listHandler;
    }

    /**
     * @return array
     */
    protected function getMemberList()
    {
        $memberList = [];

        /** @var Member $member */
        foreach ($this->listHandler->getList('ctsMemberList') as $member) {
            $memberList[$member->getDisplayName()] = $member->getDisplayName();
        }
        return $memberList;
    }

    /**
     * @param CtsPqCase $ctsCase
     *
     * @return array
     */
    protected function getMinisterList(CtsPqCase $ctsCase)
    {
        $pqHouse = $this->ctsHelper->getPQHouseFromUIN($ctsCase->getUin());

        $memberList = [];

        /** @var Member $member */
        foreach ($this->listHandler->getList('ctsMemberList') as $member) {
            if (($pqHouse == PQHouse::HOUSE_OF_LORDS && $member->getIsLords()) ||
                ($pqHouse == PQHouse::HOUSE_OF_COMMONS && !$member->getIsLords())
            ) {
                $memberList[$member->getDisplayName()] = $member->getDisplayName();
            }
        }

        return $this->ctsHelper->handleLegacyValue($memberList, $ctsCase->getMember());
    }
}
