<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Compile\Where;

use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

/**
 * Class Basic
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch\Statement\Compile\Where
 */
class Basic
{
    /**
     * @param  array  $where
     * @return string
     */
    public function getSql(array $where)
    {
        return $where['column'] . ' ' . $where['operator'] . ' \'' . $this->prepareWhereValue($where['value']) . '\'';
    }

    /**
     * @param  $value
     * @return mixed
     */
    private function prepareWhereValue($value)
    {
        return $this->boolToString($this->checkForDateTime($value));
    }

    /**
     * @param  \DateTime|string $value
     * @return null
     */
    private function checkForDateTime($value)
    {
        return $value instanceof \DateTime ? DateHelper::fromNativeOrNullToIso($value) : $value;
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    private function boolToString($value)
    {
        return is_bool($value) ? var_export($value, true) : $value;
    }
}
