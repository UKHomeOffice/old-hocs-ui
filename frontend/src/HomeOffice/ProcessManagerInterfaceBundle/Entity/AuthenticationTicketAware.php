<?php
/**
 * Created by IntelliJ IDEA.
 * User: development
 * Date: 12/06/14
 * Time: 15:45
 */
namespace HomeOffice\ProcessManagerInterfaceBundle\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Ticket;

interface AuthenticationTicketAware
{
    /**
     * @param Ticket $alfTicket
     */
    public function setAuthenticationTicket(Ticket $alfTicket);
}
