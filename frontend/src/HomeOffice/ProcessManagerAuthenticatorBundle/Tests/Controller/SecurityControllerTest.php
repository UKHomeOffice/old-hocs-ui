<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Username")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Password")')->count());
    }
}
