<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

class Paginator
{
    const PREVIOUS_PAGE_COUNT = 3;
    const NEXT_PAGE_COUNT = 3;
 
    /**
     *
     * @var int
     */
    private $pageSize = 20;
 
    /**
     * @var int
     */
    private $pageNumber;
 
    /**
     *
     * @var int
     */
    private $totalResults;
 
    /**
     * @var string
     */
    private $pagePath;
 
    /**
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
 
    /**
     *
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }
 
    /**
     *
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }
 
    /**
     *
     * @param int $pageNumber
     */
    public function setPageNumber($pageNumber)
    {
        if ($pageNumber < 1) {
            $pageNumber = 1;
        }
        $this->pageNumber = $pageNumber;
    }
 
    /**
     *
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }
 
    /**
     *
     * @param int $totalResults
     */
    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
    }
 
    /**
     *
     * @return string
     */
    public function getPagePath()
    {
        return $this->pagePath;
    }
 
    /**
     *
     * @param string $pagePath
     */
    public function setPagePath($pagePath)
    {
        $this->pagePath = $pagePath;
    }
 
    /**
     *
     * @return int
     */
    public function calculateSkipCount()
    {
        return ($this->pageNumber-1) * $this->pageSize;
    }
 
    /**
     *
     * @return int
     */
    public function calculateNumberOfPages()
    {
        return ceil($this->totalResults / $this->pageSize);
    }
 
    /**
     *
     * @return array
     */
    public function getPages()
    {
        if ($this->totalResults <= $this->getPageSize()) {
            return array();
        }
        $pages = array();
        $start = ($this->pageNumber-Paginator::PREVIOUS_PAGE_COUNT < 1)
                ? 1
                : $this->pageNumber-Paginator::PREVIOUS_PAGE_COUNT;
        $end = ($this->pageNumber+Paginator::NEXT_PAGE_COUNT > $this->calculateNumberOfPages())
               ? $this->calculateNumberOfPages()
               : $this->pageNumber+Paginator::NEXT_PAGE_COUNT;
        for ($i = $start; $i <= $end; $i++) {
            array_push($pages, $i);
        }
        return $pages;
    }

    public function showPreviousLink()
    {
        return $this->pageNumber != 1;
    }
 
    public function showNextLink()
    {
        return $this->pageNumber < $this->calculateNumberOfPages();
    }

    /**
     * Start Result
     *
     * @return int
     */
    public function startResult()
    {
        return (($this->getPageNumber() * $this->getPageSize()) - $this->getPageSize()) + 1;
    }

    /**
     * End result
     *
     * @return int
     */
    public function endResult()
    {
        $end = ($this->getPageNumber() * $this->getPageSize());

        if ($end > $this->getTotalResults()) {
            return ($this->getTotalResults() - $end) + $end;
        }

        return $end;
    }

    /**
     * Get previous page count
     *
     * @return int
     */
    public function getPreviousPageCount()
    {
        return self::PREVIOUS_PAGE_COUNT;
    }

    /**
     * Get next page count
     *
     * @return int
     */
    public function getNextPageCount()
    {
        return self::NEXT_PAGE_COUNT;
    }
}
