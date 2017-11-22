<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\CtsNode;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use Symfony\Bridge\Monolog\Logger;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

class AtomHelper
{
 
    /**
     * @var string
     */
    private $ctsNamespace;
 
    /**
     * @var CTSHelper
     */
    private $ctsHelper;
 
    /**
     * @var Logger
     */
    private $logger;
 
    /**
     *
     * @param string $ctsNamespace
     * @param CTSHelper $CtsHelper
     * @param Logger $logger
     */
    public function __construct($ctsNamespace, CTSHelper $CtsHelper, Logger $logger = null)
    {
        $this->ctsNamespace = $ctsNamespace;
        $this->ctsHelper = $CtsHelper;
        $this->logger = $logger;
    }
 
    /**
     * Create an atom entry for any instance extending CtsNode.
     * @param CtsNode $ctsNode
     * @param string $title
     * @param array $readProperties
     * @param string $requiredTypeProperty
     * @return string
     */
    public function generateAtomEntry($ctsNode, $title, $readProperties)
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'utf-8');
        $this->createRootElement($writer, 'entry', array(
            array('name' => 'xmlns', 'value' => 'http://www.w3.org/2005/Atom'),
            array('name' => 'xmlns:cmisra', 'value' => 'http://docs.oasis-open.org/ns/cmis/restatom/200908/'),
            array('name' => 'xmlns:cmis', 'value' => 'http://docs.oasis-open.org/ns/cmis/core/200908/')
        ));
        $writer->writeElement('title', $title);
        $writer->startElement('cmisra:object');
        $writer->startElement('cmis:properties');
        foreach ($readProperties as $name => $config) {
            $propertyValue = $this->getPropertyValue($config['object_prop_name'], $config['type'], $ctsNode);
            $systemField = array_key_exists('system_field', $config) ? $config['system_field'] : false;
            if ($propertyValue !== null && !$systemField) {
                $this->createPropertyElement(
                    $writer,
                    $config['type'],
                    $name,
                    $propertyValue
                );
            }
        }
        // end cmis:properties
        $writer->endElement();
        // end cmisra:object
        $writer->endElement();
        // end entry
        $writer->endElement();
        $writer->endDocument();
        return $writer->flush();
    }
 
    /**
     *
     * @param string $name
     * @param string $type
     * @param CtsCase $ctsCase
     * @return string
     */
    private function getPropertyValue($name, $type, $ctsCase)
    {

        if (property_exists($ctsCase, $name)) {
            if (method_exists($ctsCase, "get" . ucfirst($name))) {
                $methodName = "get" . ucfirst($name);
            } else {
                $methodName = 'is'.ucfirst($name);
            }
            $value = $ctsCase->$methodName();
            if ($value == null) {
                $value = '';
            }
        } else {
            return null;
        }
        switch ($type) {
            case "dateTime":
                $dateTime = DateHelper::forceDateTimeOrBlank($value);
                return is_object($dateTime) && get_class($dateTime) === DateHelper::class
                    ? $dateTime->toIso() : '';
            case "boolean":
                return $this->ctsHelper->formatBooleanForAtom($value);
            default:
                return $value;
        }
    }

    /**
     *
     * @param XmlWriter $writer
     * @param string $name
     * @param array $attributes
     * @return XmlWriter
     */
    private function createRootElement($writer, $name, $attributes = null)
    {
        $writer->startElement($name);
        if ($attributes != null) {
            foreach ($attributes as $attribute) {
                $writer->writeAttribute($attribute['name'], $attribute['value']);
            }
        }
    }
 
    /**
     *
     * @param XmlWriter $writer
     * @param string $type
     * @param string $id
     * @param string $value
     */
    private function createPropertyElement($writer, $type, $id, $value)
    {
        $writer->startElement('cmis:property' . ucfirst($type));
        $writer->writeAttribute('propertyDefinitionId', $id);
        if ($value != '') {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $writer->writeElement('cmis:value', $value);
        } else {
            $writer->text('');
        }
        $writer->endElement();
    }
 
    /**
     *
     * @param string $feed The atom feed
     * @param string $objectElemName The tag name of the repeating element containing the data eg. cmisra:object
     * @param array $readProperties Array of properties defining what to read from the feed
     * @param array $readPermissions Array of permissions defining what to read from the feed
     * @return array Array of attributes for the case in the feed
     */
    public function singleEntryFeedToArray($feed, $objectElemName, $readProperties, $readPermissions)
    {
        $cases = $this->multiEntryFeedToArray($feed, $objectElemName, $readProperties, null, $readPermissions);
        if (count($cases) > 0) {
            return $cases[0];
        } else {
            return null;
        }
    }
 
    /**
     * Return an array containing case properties, read from the $entryValues array,
     * using the $readProperties array to know what to read.
     * @param array $entryValues Array containing the values from the XML for the entry
     * @param array $readProperties Array of properties defining what to read from the feed
     * @param string $restrictProperties Restrict the properties added to the array,
     * in colloboration with the $readProperties array
     * @return array
     */
    private function atomToArray($entryValues, $readProperties, $restrictProperties)
    {
        $caseProps = array();
        foreach ($entryValues as $idx => $elem) {
            if ($elem['tag'] == 'link') {
                $linkType = 'link' . $elem['attributes']['rel'];
                if (array_key_exists($linkType, $readProperties)) {
                    $linkHref = $elem['attributes']['href'];
                    $caseProps[$readProperties[$linkType]['object_prop_name']] = $linkHref;
                }
            }
            if ($elem['type'] == 'open' && isset($elem['attributes'])) {
                $propName = $elem['attributes']['propertyDefinitionId'];
                if (array_key_exists($propName, $readProperties)) {
                    if ($restrictProperties == null ||
                        (
                            isset($readProperties[$propName][$restrictProperties]) &&
                            $readProperties[$propName][$restrictProperties]
                        )
                    ) {
                        if (isset($entryValues[$idx+1]['value'])) {
                            $propValue = $entryValues[$idx+1]['value'];
                            $caseProps[$readProperties[$propName]['object_prop_name']] = $propValue;
                        }
                    }
                }
            }
        }
        return $caseProps;
    }
 
    /**
     * Return an array containing case permissions, read from the $entryValues array,
     * using the $readPermissions array to know what to read.
     * @param array $entryValues Array containing the values from the XML for the entry
     * @param array $readPermissions  Array of permissions defining what to read from the feed
     * @return array
     */
    private function atomToArrayPermissions($entryValues, $readPermissions)
    {
        $permissionProps = array();
        foreach ($entryValues as $idx => $elem) {
            $propName = $elem['tag'];
            if (array_key_exists($propName, $readPermissions)) {
                $propValue = $elem['value'];
                $permissionProps[$readPermissions[$propName]['object_prop_name']] = $propValue;
            }
        }
        return $permissionProps;
    }
 
    /**
     * Accept a multi-entry atom xml feed and parse this into an array of cases.
     * @param string $feed The atom XML feed to read
     * @param string $objectElemName The tag name of the repeating element containing the data eg. cmisra:object
     * @param array $readProperties Array of properties defining what to read from the feed
     * @param array $restrictProperties Restrict the properties added to the array,
     * in colloboration with the $readProperties array
     * @param array $readPermissions  Array of permissions defining what to read from the feed
     * @param Paginator $paginator Paginator object
     * @return array
     */
    public function multiEntryFeedToArray(
        $feed,
        $objectElemName,
        $readProperties,
        $restrictProperties = null,
        $readPermissions = null,
        $paginator = null
    ) {
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $feed, $values, $tags);
        xml_parser_free($parser);

        $cases = array();
        //@codingStandardsIgnoreStart
        $this->logger != null ? $this->logger->info('Reading multi-entry feed, start: '. date("d-m-Y H:i:s") . substr((string)microtime(), 1, 8)) : null;
        //@codingStandardsIgnoreEnd
        foreach ($tags as $key => $val) {
            $case = array();
            if ($key == $objectElemName) {
                $entryRanges = $val;
                for ($i=0; $i < count($entryRanges); $i+=2) {
                    $offset = $entryRanges[$i] + 1;
                    $len = $entryRanges[$i + 1] - $offset;
                    $caseProps = $this->atomToArray(
                        array_slice($values, $offset, $len),
                        $readProperties,
                        $restrictProperties
                    );
                    if ($readPermissions != null) {
                        $casePermissions = $this->atomToArrayPermissions(
                            array_slice($values, $offset, $len),
                            $readPermissions
                        );
                    } else {
                        $casePermissions = array();
                    }
                    $cases[] = array_merge($caseProps, $casePermissions);
                }
            }
        }
     
        if ($paginator != null) {
            // There is only ever one opensearch:totalResults tag
            // it always is a complete tag
            // and has a 'value' containing the number of results
            $totalResults = (int)array_slice($values, $tags['opensearch:totalResults'][0], 1)[0]['value'];
            $paginator->setTotalResults($totalResults);
        }
     
        //@codingStandardsIgnoreStart
        $this->logger != null ? $this->logger->info('Reading multi-entry feed, end: '. date("d-m-Y H:i:s") . substr((string)microtime(), 1, 8)) : null;
        //@codingStandardsIgnoreEnd
        return $cases;
    }
}
