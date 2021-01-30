<?php

namespace ComplyCube\Model;

use \stdClass;

class Address implements \JsonSerializable
{
    public $id;
    public $clientId;
    public $type;
    public $propertyNumber;
    public $buildingName;
    public $line;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    public $fromDate;
    public $toDate;
    protected $lastActionBy;
    protected $createdAt;
    protected $updatedAt;

    public function load(stdClass $response)
    {
        $this->id = $response->id;
        $this->clientId = $response->clientId;
        $this->type = isset($response->type) ?  $response->type : null;
        $this->propertyNumber = isset($response->propertyNumber) ?  $response->propertyNumber : null;
        $this->buildingName = isset($response->buildingName) ?  $response->buildingName : null;
        $this->line = $response->line;
        $this->city = $response->city;
        $this->state = isset($response->state) ?  $response->state : null;
        $this->postalCode = isset($response->postalCode) ?  $response->postalCode : null;
        $this->country = $response->country;
        $this->fromDate = isset($response->fromDate) ?  $response->fromDate : null;
        $this->toDate = isset($response->toDate) ?  $response->toDate : null;
        $this->lastActionBy = isset($response->lastActionBy) ? $response->lastActionBy : null;
        $this->createdAt = isset($response->createdAt) ? $response->createdAt : null;
        $this->updatedAt = isset($response->updatedAt) ? $response->updatedAt : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id' => $this->id,
            'clientId' => $this->clientId,
            'type' => $this->type,
            'propertyNumber' => $this->propertyNumber,
            'buildingName' => $this->buildingName,
            'line' => $this->line,
            'city' => $this->city,
            'state' => $this->state,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate
        ], function ($value) {
            return ($value !== null);
        });
    }
}
