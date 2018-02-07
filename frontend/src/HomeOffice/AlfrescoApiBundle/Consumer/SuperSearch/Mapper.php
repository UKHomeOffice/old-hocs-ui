<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\BusinessUnit;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\CaseId;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\CorrespondingName;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\DateFrom;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\DateTo;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\MapperInterface;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\ReviewedBy;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\SignedBy;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\Keyword;
use HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Mapper\ThirdPartyCorrespondent;

/**
 * Class Mapper
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement
 */
class Mapper extends Statement
{
    /**
     * @var array
     */
    private $field = [
        'businessUnit'          => 'c.cts:correspondenceType',
        'status'                => 'c.cts:caseStatus',
        'createdFrom'           => 'c.cmis:creationDate',
        'createdTo'             => 'c.cmis:creationDate',
        'deadlineFrom'          => 'c.cts:caseResponseDeadline',
        'deadlineTo'            => 'c.cts:caseResponseDeadline',
        'caseId'                => 'c.cts:urnSuffix',
        'markupMinister'        => 'c.cts:markupMinister',
        'task'                  => 'c.cts:caseTask',
        'caseType'              => 'c.cts:correspondenceType',
        'signedBy'              => 'c.cts:signedByHomeSec',
        'reviewedBy'            => 'reviewedBy',
        'passportNumber'        => 'c.cts:hmpoPassportNumber',
        'applicantNumber'       => 'c.cts:hmpoApplicationNumber',
        'dateReceivedFrom'      => 'c.cts:dateReceived',
        'dateReceivedTo'        => 'c.cts:dateReceived',
        'suitableForDisclosure' => 'c.cts:foiDisclosure',
        'mpReference'           => 'c.cts:mpRef',
        'hmpoComplaintType'     => 'c.cts:hmpoStage',
        'team'                  => 'c.cts:markupTeam',
    ];

    /**
     * @var array
     */
    private $factory = [
        'businessUnit' => BusinessUnit::class,
        'caseId'       => CaseId::class,
        'reviewedBy'   => ReviewedBy::class,
        'signedBy'     => SignedBy::class,
        'keyword'      => Keyword::class
    ];

    /**
     * @var array
     */
    private $alias = [
        'createdFrom'           => DateFrom::class,
        'createdTo'             => DateTo::class,
        'deadlineFrom'          => DateFrom::class,
        'deadlineTo'            => DateTo::class,
        'dateReceivedFrom'      => DateFrom::class,
        'dateReceivedTo'        => DateTo::class,
        'correspondentForename' => ThirdPartyCorrespondent::class,
        'correspondentSurname'  => ThirdPartyCorrespondent::class,
        'correspondentPostcode' => ThirdPartyCorrespondent::class,
        'correspondentEmail'    => ThirdPartyCorrespondent::class
    ];

    private $businessUnit = false;
    
    /**
     * @param  array $parameters
     * @return $this
     */
    public function map(array $parameters)
    {
        $this->businessUnit = isset($parameters['businessUnit']) ? $parameters['businessUnit'] : false;
        $this->loop($this->overrides($parameters));
        return $this;
    }

    /**
     * @param array $parameters
     */
    private function loop(array $parameters)
    {
        foreach($parameters as $field => $value) {
            if (is_null($value) || $value == '') {
                continue;
            }
            $this->object($field, $value);
        }
    }

    /**
     * @param  array $parameters
     * @return array
     */
    private function overrides(array $parameters)
    {
        return $this->caseTypeOverride($parameters);
    }

    /**
     * @param  array $parameters
     * @return array
     */
    private function caseTypeOverride(array $parameters)
    {
        if (isset($parameters['caseType']) && $parameters['caseType'] !== '') {
            unset($parameters['businessUnit']);
        }

        return $parameters;
    }

    public function getBusinessUnit()
    {
        return $this->businessUnit;
    }
    
    /**
     * @param  $field
     * @param  $value
     * @return Mapper|Statement|mixed
     */
    private function object($field, $value)
    {
        return array_key_exists($field, $this->factory)
            ? $this->mapper($this->factory[$field])->map($this, $this->mapField($field), $value)
            : $this->alias($field, $value);
    }

    /**
     * @param  $field
     * @param  $value
     * @return Mapper|Statement|mixed
     */
    private function alias($field, $value)
    {
        return array_key_exists($field, $this->alias)
            ? $this->mapper($this->alias[$field])->map($this, $this->mapField($field), $value)
            : $this->field($field, $value);
    }

    /**
     * @param  $field
     * @param  $value
     * @return $this|Statement
     */
    private function field($field, $value)
    {
        return $this->where($this->mapField($field), $value);
    }

    /**
     * @param  $field
     * @return string
     */
    private function mapField($field)
    {
        return array_key_exists($field, $this->field) ? $this->field[$field] : 'c.cts:' . $field;
    }

    /**
     * @param  string          $class
     * @return MapperInterface
     */
    private function mapper($class)
    {
        return new $class;
    }

}
