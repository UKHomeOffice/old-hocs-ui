<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use Symfony\Component\Config\ConfigCache;

final class RepositoryInfo extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/cmis';

    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @param  array        $options
     * @param  bool         $listHandler
     * @return \DOMDocument
     */
    public function get(array $options = [], $listHandler = false)
    {
        $this->dom = new \DOMDocument;
        $this->dom->loadXML(parent::get($options, $listHandler));

        return $this->dom;
    }

    /**
     * @param  $tagName
     * @param  array    $options
     * @param  bool     $listHandler
     * @param  array    $return
     * @return array
     */
    public function getNodes($tagName, $options = [], $listHandler = false, $return = [])
    {
        $this->get($options, $listHandler);

        foreach ($this->dom->getelementsByTagName($tagName) as $value) {
            $return[] = $value->nodeValue;
        }

        return $return;
    }

    /**
     * @param  array $options
     * @param  bool  $listHandler
     * @return mixed
     */
    public function getRepositoryId($options = [], $listHandler = false)
    {
        return $this->getNodes('repositoryId', $options, $listHandler)[0];
    }
}
