<?php

namespace HomeOffice\CtsBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsHelpDocument;

class HelpControllerTest extends WebTestCase
{
 
    public function testGetHelpDocument()
    {
        $ctsHelpDocument = new CtsHelpDocument('workspace', 'SpacesStore');
        $ctsHelpDocument->setId('workspace://SpacesStore/1234');
        $ctsHelpDocument->setName('Help test doc');
     
        $this->mockCtsHelpDocumentRepo('getHelpDocuments', array($ctsHelpDocument));

        $crawler = $this->client->request(
            'GET',
            '/cts/help',
            array( 'helpDocuments' => array($ctsHelpDocument)
            )
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }
}
