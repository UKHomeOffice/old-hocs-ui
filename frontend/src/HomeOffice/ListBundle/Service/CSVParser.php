<?php

namespace HomeOffice\ListBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Member;

class CSVParser
{

    public function toCollapsedArray($csv, $keyIndex = 0, $dataIndex = 0)
    {
        return $this->collapseCsvArray($this->toArray($csv), $keyIndex, $dataIndex);
    }
 
    /**
     * Convert a CSV string to a multi-dimensional array.
     * @param $csv
     * @return array
     */
    public function toArray($csv, $ignoreFirstLine = true)
    {
        $csv = preg_replace("/[\r\n]{1,}/", "\r", $csv);
        $lines = explode("\r", $csv);
        $array = array();
        foreach ($lines as $idx => $line) {
            if ($idx == 0 && $ignoreFirstLine) {
                continue;
            }
            if ($line != null) {
                $array[] = str_getcsv($line);
            }
        }
        return $array;
    }
 
    /**
     * Flatten a multi-dimensional array created from a CSV string into
     * a single dimension with key and value the same.
     * @param array $csvArray
     * @return array
     */
    public function collapseCsvArray($csvArray, $keyIndex = 0, $dataIndex = 0)
    {
        $collapsedArray = array();
        foreach ($csvArray as $val) {
            $collapsedArray[trim($val[$keyIndex])] = trim($val[$dataIndex]);
        }
        asort($collapsedArray);
        return $collapsedArray;
    }
 
    public function extractFromList($list, $retrieveIndex, $checkIndex = null, $checkValue = null)
    {
        $listArray = array();
        foreach ($list as $val) {
            if ($checkIndex != null) {
                if (strtolower($val[$checkIndex]) == $checkValue &&
                    !array_key_exists($val[$retrieveIndex], $listArray)
                ) {
                    $listArray[$val[$retrieveIndex]] = $val[$retrieveIndex];
                }
            } else {
                if (!array_key_exists($val[$retrieveIndex], $listArray)) {
                    $listArray[$val[$retrieveIndex]] = $val[$retrieveIndex];
                }
            }
        }
        asort($listArray);
        return $listArray;
    }
 
    /**
     *
     * @param array $ctsCorrespondentList
     * @return array
     */
    public function extractMembersFromList($ctsCorrespondentList)
    {
        $politicanListArray = array();

        foreach ($ctsCorrespondentList as $val) {
            if ((strtolower($val[5]) == "true" || strtolower($val[6]))
                && !array_key_exists($val[4], $politicanListArray)) {
                    $politicanListArray[$val[4]] = new Member($val);
            }
        }
        asort($politicanListArray);
        return $politicanListArray;
    }
}
