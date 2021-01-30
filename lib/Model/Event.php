<?php

namespace ComplyCube\Model;

class Event implements \JsonSerializable
{
    public $id;
    public $type;
    public $resourceType;
    public $payload;
    public $createdAt;

    public function load(\stdClass $response)
    {
        $this->id = $response->id;
        $this->type = isset($response->type) ?  $response->type : null;
        $this->resourceType = isset($response->resourceType) ?  $response->resourceType : null;
        $this->payload = isset($response->payload) ?  $response->payload : null;
        $this->createdAt = isset($response->createdAt) ? $response->createdAt : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id' => $this->id,
            'type' => $this->type,
            'resourceType' => $this->resourceType,
            'payload' => $this->payload,
            'createdAt' => $this->createdAt
        ], function ($value) {
            return ($value !== null);
        });
    }
}
