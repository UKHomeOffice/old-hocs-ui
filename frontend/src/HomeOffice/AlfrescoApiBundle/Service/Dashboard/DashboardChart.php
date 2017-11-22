<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Dashboard;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\TaskStatus;

/**
 * Class DashboardChart
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Dashboard
 */
class DashboardChart
{
    /**
     * @var array
     */
    private $originalData;

    /**
     * @var DashboardElement[]
     */
    private $elements = [];

    private $colors = [
        '#2E358B',
        '#D53880',
        '#DF3034',
        '#FFBF47',
        '#28A197',
        '#6F72AF',
        '#F499BE',
        '#F47738',
        '#006435',
        '#2B8CC4',
        '#912B88',
        '#B10E1E',
        '#B58840',
        '#85994B',
        '#005EA5',
    ];

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->compileData();
    }

    /**
     * @param array $selectBy
     *
     * @return array
     */
    public function getElementsBySelection(array $selectBy)
    {
        $selectByMethod = 'get'.ucwords($selectBy['field']);

        $results = [];
        foreach ($this->elements as $element) {
            if ($element->{$selectByMethod}() === $selectBy['value']) {
                $results[] = $element;
            }
        }

        return $results;
    }

    /**
     * @param DashboardElement[] $data
     * @param string             $groupBy
     *
     * @return array
     */
    public function getGroups(array $data, $groupBy)
    {
        $groupByMethod = 'get'.ucwords($groupBy);

        $groups = [];
        foreach ($data as $element) {
            if (!in_array($element->{$groupByMethod}(), $groups)) {
                $groups[] = $element->{$groupByMethod}();
            }
        }

        return $groups;
    }

    /**
     * @param DashboardElement[] $data
     * @param string             $position
     * @param string             $groupBy
     * @param string             $group
     *
     * @return int
     */
    public function getCount(array $data, $position, $groupBy, $group)
    {
        $groupByMethod = 'get'.ucwords($groupBy);
        $positionMethod = 'get'.ucwords($position);

        $count = 0;
        foreach ($data as $element) {
            if ($element->{$groupByMethod}() == $group) {
                $count += $element->{$positionMethod}();
            }
        }

        return $count;
    }

    /**
     * @param DashboardElement[] $elements
     * @param string             $groupBy
     *
     * @return array
     */
    public function getTableData(array $elements, $groupBy)
    {
        $groupByMethod = 'get'.ucwords($groupBy);
        $data = [];
        foreach ($elements as $element) {
            if (!array_key_exists($element->{$groupByMethod}(), $data)) {
                $data[$element->{$groupByMethod}()] = [
                    'open' => 0,
                    'openAndOverdue' => 0,
                    'returned' => 0,
                ];
            }
            $data[$element->{$groupByMethod}()]['open'] += $element->getOpen();
            $data[$element->{$groupByMethod}()]['openAndOverdue'] += $element->getOpenAndOverdue();
            $data[$element->{$groupByMethod}()]['returned'] += $element->getReturned();
        }

        return $data;
    }

    /**
     * @param DashboardElement[] $elements
     * @param string             $groupBy
     * @param string             $position
     *
     * @return array
     */
    public function getChartData(array $elements, $groupBy, $position)
    {
        $groupByMethod = 'get'.ucwords($groupBy);
        $positionMethod = 'get'.ucwords($position);

        $data = [];
        foreach ($elements as $element) {
            if (!array_key_exists($element->{$groupByMethod}(), $data)) {
                $data[$element->{$groupByMethod}()] = 0;
            }

            $data[$element->{$groupByMethod}()] += $element->{$positionMethod}();
        }

        return $data;
    }

    /**
     * @param bool $empty
     *
     * @return array
     */
    public function getColors($empty = false)
    {
        if ($empty === true) {
            return ['#f8f8f8'];
        }

        return $this->colors;
    }

    /**
     * @param int $index
     *
     * @return string
     */
    public function getColor($index)
    {
        if ($index > (count($this->colors)-1)) {
            // Loop round the clock to reuse colors
            return $this->getColor($index - count($this->colors));
        }

        if (isset($this->colors[$index])) {
            return $this->colors[$index];
        }

        return '#f8f8f8';
    }

    /**
     * @param string $state
     *
     * @return string
     */
    public function getPositionLabel($state)
    {
        switch ($state) {
            case 'open':
                $label = 'Open';
                break;
            case 'openAndOverdue':
                $label = 'Overdue at stage';
                break;
            case 'returned':
                $label = 'Returned';
                break;
            default:
                $label = $state;
        }

        return $label;
    }

    /**
     * @param DashboardElement[] $elements
     * @param string             $position
     *
     * @return int
     */
    public function getPositionTotal(array $elements, $position)
    {
        $positionMethod = 'get'.ucwords($position);

        $total = 0;
        foreach ($elements as $element) {
            $total += $element->{$positionMethod}();
        }

        return $total;
    }

    /**
     * Compile Grouped Data
     */
    private function compileData()
    {
        foreach ($this->originalData as $correspondenceType => $tasks) {
            $caseType = CaseCorrespondenceSubType::getCaseType($correspondenceType);

            foreach ($tasks as $task => $positions) {
                $element = $this->hydrateElement(
                    $caseType,
                    $correspondenceType,
                    TaskStatus::getStatusForTask($task),
                    $task
                );
                $element
                    ->setOpen($positions['open'])
                    ->setOpenAndOverdue($positions['openAndOverdue'])
                    ->setReturned($positions['returned'])
                ;
            }
        }

        usort($this->elements, function($a, $b)
        {
            return $a->getScore() <= $b->getScore();
        });
    }

    /**
     * @param string $caseType
     * @param string $correspondenceType
     * @param string $status
     * @param string $task
     *
     * @return DashboardElement
     */
    private function hydrateElement($caseType, $correspondenceType, $status, $task)
    {
        foreach ($this->elements as $element) {
            if ($element->getCaseType() === $caseType &&
                $element->getCorrespondenceType() === $correspondenceType &&
                $element->getStatus() === $status &&
                $element->getTask() === $task
            ) {
                return $element;
            }
        }

        $element = new DashboardElement($caseType, $correspondenceType, $status, $task);
        array_push($this->elements, $element);

        return $element;
    }
}
