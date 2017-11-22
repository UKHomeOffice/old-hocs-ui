<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Ticket;

class TicketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ticket
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new Ticket();
    }

    public function testGetSetTicket()
    {
        $this->instance->setTicket("testing");

        $this->assertEquals("testing", $this->instance->getTicket());
    }

    public function testCastingToString()
    {
        $this->instance->setTicket("Ticket");

        $this->assertEquals("Ticket", (string) $this->instance);
    }
}
