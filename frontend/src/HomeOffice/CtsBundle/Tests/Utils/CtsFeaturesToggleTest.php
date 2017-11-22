<?php namespace HomeOffice\CtsBundle\Tests\Utils;

use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class CtsFeaturesToggleTest extends \PHPUnit_Framework_TestCase
{
    private $ctsFeaturesToggle;
    private $session;
    private $sessionName;

    public function setUp()
    {
        $this->session = new Session(new MockArraySessionStorage);
        $this->ctsFeaturesToggle = new CtsFeaturesToggle($this->session);
        $reflection = new \ReflectionClass('HomeOffice\CtsBundle\Utils\CtsFeaturesToggle');
        $reflectionProperty = $reflection->getProperty('sessionSetting');
        $reflectionProperty->setAccessible(true);
        $this->sessionName = $reflectionProperty->getValue(new CtsFeaturesToggle($this->session));
    }

    public function testOn()
    {
        $this->session->set($this->sessionName, false);
        $this->ctsFeaturesToggle->on();
        $this->assertTrue($this->ctsFeaturesToggle->get());
    }

    public function testOff()
    {

        $this->session->set($this->sessionName, true);
        $this->ctsFeaturesToggle->off();
        $this->assertFalse($this->ctsFeaturesToggle->get());
    }

    public function testGet()
    {
        $this->ctsFeaturesToggle->on();
        $this->assertTrue($this->ctsFeaturesToggle->get());
        $this->ctsFeaturesToggle->off();
        $this->assertFalse($this->ctsFeaturesToggle->get());
    }
}
