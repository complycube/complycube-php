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
    public $ssn;
    public $socialInsuranceNumber;
    public $nationalIdentityNumber;
    public $taxIdentificationNumber;


    public function load(stdClass $response)
    {
        $this->firstName = $response->firstName;
        $this->lastName = $response->lastName;
        $this->dob = isset($response->dob) ? $response->dob : null;
        $this->gender = isset($response->gender) ? $response->gender : null;
        $this->nationality = isset($response->nationality) ? $response->nationality : null;
        $this->birthCountry = isset($response->birthCountry) ? $response->birthCountry : null;
        $this->ssn = isset($response->ssn) ? $response->ssn : null;
        $this->socialInsuranceNumber = isset($response->socialInsuranceNumber) ? $response->socialInsuranceNumber : null;
        $this->nationalIdentityNumber = isset($response->nationalIdentityNumber) ? $response->nationalIdentityNumber : null;
        $this->taxIdentificationNumber = isset($response->taxIdentificationNumber) ? $response->taxIdentificationNumber : null;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'birthCountry' => $this->birthCountry,
            'ssn' => $this->ssn,
            'socialInsuranceNumber' => $this->socialInsuranceNumber,
            'nationalIdentityNumber' => $this->nationalIdentityNumber,
            'taxIdentificationNumber' => $this->taxIdentificationNumber,
        ], function ($value) {
            return ($value !== null);
        });
    }
}
