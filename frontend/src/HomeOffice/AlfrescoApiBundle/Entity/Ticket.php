<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

class Ticket
{
    /**
     * @var string
     */
    private $ticket;

    /**
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTicket() != null ? $this->getTicket() : 'INVALID';
    }

    /**
     * @param string $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }
}
