<?php namespace HomeOffice\AlfrescoApiBundle\Mapper;

/**
 * Class MarkupUnit                            
 * 
 * @package HomeOffice\AlfrescoApiBundle\Mapper
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-08-05
 */
final class MarkupUnit extends AbstractMapper
{
    /**       
     * Map
     * 
     * @param array $response
     * @param bool $listHandler
     * @return array
     */
    public function map(array $response, $listHandler = false)
    {
        $this->replacements = $listHandler->getList('ctsUnitList');

        foreach($response['ctsCases'] as $key => $value) {
            if (isset($value['case']['markupUnit'])) {
                $response['ctsCases'][$key]['case']['markupUnit'] = $this->match($value['case']['markupUnit']);
            }
        }

        return $response;
    }
}
