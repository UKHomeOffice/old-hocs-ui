<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch;

/**
 * Class Paginator
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch
 */
class Paginator
{
    /**
     * @var array
     */
    protected $results = [];

    /**
     * @var int
     */
    protected $numItems = 0;

    /**
     * @var bool
     */
    protected $hasMoreItems = false;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * Constructor
     *
     * @param array $results
     * @param int   $numItems
     * @param bool  $hasMoreItems
     * @param int   $limit
     * @param int   $offset
     */
    public function __construct(array $results, $numItems, $hasMoreItems, $limit, $offset)
    {
        $this->results = $results;
        $this->numItems = $numItems;
        $this->hasMoreItems = $hasMoreItems;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * Get Results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->offset + 1;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        $to = $this->offset + $this->limit;

        return $to < $this->numItems ? $to : $this->numItems;
    }

    /**
     * Get NumItems
     *
     * @return int
     */
    public function getNumItems()
    {
        return $this->numItems;
    }

    /**
     * @return bool
     */
    public function hasMoreItems()
    {
        return $this->hasMoreItems;
    }

    /**
     * Get Limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return float
     */
    public function getKnownPages()
    {

        return ceil($this->numItems / $this->limit);
    }

    /**
     * @return float
     */
    public function getCurrentPage()
    {
        return ceil($this->offset / $this->limit) + 1;
    }

    /**
     * @param int $page
     *
     * @return int
     */
    public function getPageOffset($page)
    {
        return ($page - 1) * $this->limit;
    }

    /**
     * @return int
     */
    public function getNextOffset()
    {
        return $this->offset + $this->limit;
    }

    /**
     * @return int
     */
    public function getPreviousOffset()
    {
        return $this->offset - $this->limit;
    }
}
