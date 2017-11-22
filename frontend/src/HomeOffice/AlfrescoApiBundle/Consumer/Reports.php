<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

/**
 * Class Reports
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 */
class Reports extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 'api/-default-/public/cmis/versions/1.1/browser/root/Sites/cts/CTS';

    /**
     * Get Report List
     *
     * @return string[]
     */
    public function getList()
    {
        $businessUnits = [];


        $response = $this->get(['cmisselector' => 'children']);

        if (isset($response['objects'])) {
            foreach ($response['objects'] as $object) {
                $businessUnits[] = $object['object']['properties']['cmis:name']['value'];
            }
        }

        return $businessUnits;
    }

    /**
     * Get Reports
     *
     * @param string $businessUnit
     *
     * @return array
     */
    public function getReports($businessUnit)
    {
        $this->url = $this->url . '/' . strtoupper($businessUnit);
        $reports = [];


        $response = $this->get(['cmisselector' => 'children']);

        if (isset($response['objects'])) {
            foreach ($response['objects'] as $object) {
                $reports[] = [
                    'name' => $object['object']['properties']['cmis:name']['value'],
                    'objectId' => $object['object']['properties']['cmis:objectId']['value'],
                ];
            }
        }

        return $reports;
    }

    /**
     * Get Reports
     *
     * @param string $businessUnit
     *
     * @return array
     */
    public function getReport($objectId)
    {
        $this->url = 'api/-default-/public/cmis/versions/1.1/browser/root';

        $response = $this->get(['objectId' => $objectId]);

        return str_replace("\N", ' ', $response);
    }


}
