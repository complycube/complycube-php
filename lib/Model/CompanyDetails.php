<?php

namespace ComplyCube\Model;

use \stdClass;

class CompanyDetails implements \JsonSerializable
{
    public $name;
    public $website;
    public $registrationNumber;
    public $incorporationCountry;
    public $incorporationType;

    public function load(stdClass $response)
    {
        $this->name = $response->name;
        $this->website = isset($response->website) ? $response->website : null;
        $this->registrationNumber = isset($response->registrationNumber) ? $response->registrationNumber : null;
        $this->incorporationCountry = isset($response->incorporationCountry) ? $response->incorporationCountry : null;
        $this->incorporationType = isset($response->incorporationType) ? $response->incorporationType : null;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'name' => $this->name,
            'website' => $this->website,
            'registrationNumber' => $this->registrationNumber,
            'incorporationCountry' => $this->incorporationCountry,
            'incorporationType' => $this->incorporationType
        ], function ($value) {
            return ($value !== null);
        });
    }
}
