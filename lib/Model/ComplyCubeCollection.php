<?php

namespace ComplyCube\Model;

use ComplyCube\ComplyCubeClient;

class ComplyCubeCollection implements \Iterator
{
    public $page;
    public $items = [];
    public $pages;
    public $pageSize;
    public $totalSize;
    private $position = 0;

    public function __construct(string $model, \stdClass $apiResponse)
    {
        $this->page = $apiResponse->page;
        if (property_exists($apiResponse, 'items')) {
            $this->items = $apiResponse->items;
        }
        $this->pages = $apiResponse->pages;
        $this->pageSize = $apiResponse->pageSize;
        $this->totalItems = $apiResponse->totalItems;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }
}
