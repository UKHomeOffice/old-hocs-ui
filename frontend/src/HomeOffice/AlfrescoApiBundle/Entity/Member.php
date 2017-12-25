<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Member
 *
 * @author jackm
 */
class Member
{
    /*
     * @var string
     */
    private $listName;
 
    /*
     * @var string
     */
    private $displayName;
 
    /*
     * @var string
     */
    private $memberId;
         
    /*
     * @var string
     */
    private $party;
 
    /*
     * @var string
     */
    private $constituency;
     
    /*
     * @var boolean
     */
    private $isLords;
       
    /**
     *
     * @param array $entity
     */
    public function __construct($entity)
    {
        $this->setListName($entity->value);
        $this->setDisplayName($entity->text);
        $this->setParty('');
        $this->setConstituency('');
        $this->setMemberId('');
        $this->setIsLords($entity->properties->house == 'Lords');
    }
 
    /**
     *
     * @return string
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     *
     * @return string
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     *
     * @return string
     */
    public function getConstituency()
    {
        return $this->constituency;
    }

    /**
     *
     * @param string $listName
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    /**
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     *
     * @param string $party
     */
    public function setParty($party)
    {
        $this->party = $party;
    }

    /**
     *
     * @param string $constituency
     */
    public function setConstituency($constituency)
    {
        $this->constituency = $constituency;
    }
 
    /**
     *
     * @return boolean
     */
    public function getIsLords()
    {
        return strtolower($this->isLords) == "true" ? true : false;
    }

    /**
     *
     * @param boolean $isLords
     */
    public function setIsLords($isLords)
    {
        $this->isLords = $isLords;
    }
 
    /**
     *
     * @return boolean
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     *
     * @param boolean $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }
}
