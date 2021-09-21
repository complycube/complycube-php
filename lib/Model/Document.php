<?php

namespace ComplyCube\Model;

use \stdClass;

class Document implements \JsonSerializable
{
    public $id = null;
    public $clientId = null;
    public $type = null;
    public $classification = null;
    public $issuingCountry = null;
    protected $createdAt;
    protected $updatedAt;

    public function load(stdClass $response)
    {
        $this->id = $response->id;
        $this->clientId = $response->clientId;
        $this->type = $response->type;
        $this->classification = isset($response->classification) ?  $response->classification : null;
        $this->issuingCountry = isset($response->issuingCountry) ?  $response->issuingCountry : null;
        $this->createdAt = isset($response->createdAt) ? $response->createdAt : null;
        $this->updatedAt = isset($response->updatedAt) ? $response->updatedAt : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id' => $this->id,
            'clientId' => $this->clientId,
            'type' => $this->type,
            'classification' => $this->classification,
            'issuingCountry' => $this->issuingCountry,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ], function ($value) {
            return ($value !== null);
        });
    }
}
