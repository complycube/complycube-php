<?php

namespace ComplyCube\Model;

use \stdClass;

class Client implements \JsonSerializable
{
    public $id;
    public $type;
    public $entityName;
    public $email;
    public $mobile;
    public $telephone;
    public $joinedDate;
    public $personDetails;
    public $companyDetails;
    protected $lastActionBy;
    protected $createdAt;
    protected $updatedAt;

    public function load(stdClass $response)
    {
        $this->id = $response->id;
        $this->type = $response->type;
        $this->entityName = isset($response->entityName) ?  $response->entityName : null;
        $this->email = $response->email;
        $this->mobile = isset($response->mobile) ?  $response->mobile : null;
        $this->telephone = isset($response->telephone) ?  $response->telephone : null;
        if (isset($response->personDetails)) {
            $pd = new PersonDetails();
            $pd->load($response->personDetails);
            $this->personDetails = $pd;
            $this->companyDetails = null;
        }
        if (isset($response->companyDetails)) {
            $cd = new CompanyDetails();
            $cd->load($response->companyDetails);
            $this->companyDetails = $cd;
            $this->personDetails = null;
        }
        $this->lastActionBy = isset($response->lastActionBy) ? $response->lastActionBy : null;
        $this->createdAt = isset($response->createdAt) ? $response->createdAt : null;
        $this->updatedAt = isset($response->updatedAt) ? $response->updatedAt : null;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'id' => $this->id,
            'type' => $this->type,
            'entityName' => $this->entityName,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'telephone' => $this->telephone,
            'personDetails' => $this->personDetails,
            'companyDetails' => $this->companyDetails,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ], function ($value) {
            return ($value !== null);
        });
    }
}
