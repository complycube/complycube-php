<?php

namespace ComplyCube\Model;

use \stdClass;

class Report implements \JsonSerializable
{
    public $contentType = null;
    public $data = null;

    public function load(stdClass $response)
    {
        $this->contentType = $response->contentType;
        $this->data = $response->data;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'contentType' => $this->contentType,
            'data' => $this->data
        ], function ($value) {
            return ($value !== null);
        });
    }
}
