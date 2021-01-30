<?php

namespace ComplyCube\Model;

use \stdClass;

class PersonDetails implements \JsonSerializable
{
    public $firstName;
    public $lastName;
    public $dob;
    public $gender;
    public $nationality;
    public $birthCountry;

    public function load(stdClass $response)
    {
        $this->firstName = $response->firstName;
        $this->lastName = $response->lastName;
        $this->dob = isset($response->dob) ? $response->dob : null;
        $this->gender = isset($response->gender) ? $response->gender : null;
        $this->nationality = isset($response->nationality) ? $response->nationality : null;
        $this->birthCountry = isset($response->birthCountry) ? $response->birthCountry : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'birthCountry' => $this->birthCountry
        ], function ($value) {
            return ($value !== null);
        });
    }
}
