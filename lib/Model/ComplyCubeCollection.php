<?php

namespace ComplyCube\Model;

use Iterator;
use stdClass;

class ComplyCubeCollection extends Model implements Iterator
{
    public int $page;
    public int $pageSize;
    public int $totalItems;
    public int $pages;
    public ?array $items;
    private int $position;
    private string $model;

    public function __construct(string $model, stdClass $apiResponse)
    {
        $this->model = $model;

        $this->load($apiResponse);
    }

    public function load(stdClass $response): void
    {
        $this->page = property_exists($response, "page") ? $response->page : 1;

        $this->pageSize = property_exists($response, "pageSize")
            ? $response->pageSize
            : 100;

        $this->totalItems = property_exists($response, "totalItems")
            ? $response->totalItems
            : 0;

        $this->pages = property_exists($response, "pages")
            ? $response->pages
            : 0;

        if (property_exists($response, "items")) {
            foreach ($response->items as $item) {
                $this->items[] = new $this->model($item);
            }
        } else {
            $this->items = null;
        }

        $this->rewind();
    }

    public function jsonSerialize()
    {
        return array_filter(
            parent::jsonSerialize(),
            fn($key) => !in_array($key, ["position", "model"]),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function rewind(): void
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

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
