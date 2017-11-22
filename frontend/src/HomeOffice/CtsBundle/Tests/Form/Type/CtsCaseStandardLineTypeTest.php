<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsCaseStandardLineType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseStandardLineFactory;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseStandardLineTypeTest extends TypeTestCase
{
 
    /**
     * @var  vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        parent::setUp();
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newFile('testFile.txt')->at($this->root)->setContent("test file contents");
        $this->fileExample = vfsStream::url('root/testFile.txt');
    }
 
    public function testSubmitValidData()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
     
        $formData = array(
            'associatedUnit' => 'UNIT 1',
            'associatedTopic' => 'Every',
            'reviewDate' => null,
            'file' => $file
        );
     
        $newData = array(
            'associatedUnit' => 'UNIT 2',
            'associatedTopic' => 'Hazelenut',
            'reviewDate' => null,
            'file' => $file
        );
     
        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);

        $factory = new CtsCaseStandardLineFactory('workspace', 'store', $ctsHelper);
        $ctsCaseStandardLine = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine');
        $ctsNewCaseStandardLine = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine');
     
        $type = new CtsCaseStandardLineType('workspace', 'store', 'create', $ctsListHandler, $ctsHelper);
        $form = $this->factory->create($type, $ctsCaseStandardLine);

        $form->submit($newData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsNewCaseStandardLine, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
