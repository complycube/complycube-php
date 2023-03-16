<?php

namespace ComplyCube\Model;

use \stdClass;

class LiveVideo implements \JsonSerializable
{
    public ?string $id = null;
    public ?string $clientId = null;
    public ?string $language = null;
    public $challenges = [];
    protected $createdAt;
    protected $updatedAt;

    public function load(stdClass $response)
    {
        $this->id = isset($response->id) ? $response->id : null;
        $this->clientId = isset($response->clientId) ? $response->clientId : null;
        $this->language = isset($response->language) ? $response->language : null;
        $this->challenges = isset($response->challenges) ? $response->challenges : null;
        $this->createdAt = isset($response->createdAt) ? $response->createdAt : null;
        $this->updatedAt = isset($response->updatedAt) ? $response->updatedAt : null;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'id' => $this->id,
            'clientId' => $this->clientId,
            'language' => $this->language,
            'challenges' => $this->challenges,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ], function ($value) {
            return ($value !== null);
        });
    }
}
