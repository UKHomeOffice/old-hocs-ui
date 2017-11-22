<?php

namespace HomeOffice\CtsBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;

class FeatureToggleControllerTest extends WebTestCase
{
    public function testGet()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Feature Toggle');

        $crawler = $this->client->request('GET', '/cts/featuretoggle');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Feature Toggle", $crawler->filter('h2')->text());
    }

    public function testEditToggle()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request(
            'POST',
            '/cts/featuretoggle',
            ['featureToggle_featureToggle_1' => 1]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
