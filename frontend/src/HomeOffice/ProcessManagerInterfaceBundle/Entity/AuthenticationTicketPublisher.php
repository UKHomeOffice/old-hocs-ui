<?php
/**
 * Created by IntelliJ IDEA.
 * User: development
 * Date: 12/06/14
 * Time: 15:45
 */
namespace HomeOffice\ProcessManagerInterfaceBundle\Entity;

interface AuthenticationTicketPublisher
{

    /**
     * @param AuthenticationTicketAware $listener
     * @return mixed
     */
    public function addAuthenticationTicketListener(AuthenticationTicketAware $listener);
}
