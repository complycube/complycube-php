<?php

namespace ComplyCube\Model;

class TeamMember implements \JsonSerializable
{
    public $id = null;
    public $firstName = null;
    public $lastName = null;
    public $role = null;
    public $createdAt = null;

    public function load(\stdClass $response)
    {
        $this->id = $response->id;
        $this->firstName = isset($response->firstName) ? $response->firstName : null;
        $this->lastName = isset($response->lastName) ? $response->lastName : null;
        $this->role = $response->role;
        $this->createdAt = $response->createdAt;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'role' => $this->role,
            'createdAt' => $this->createdAt
        ], function ($value) {
            return ($value !== null);
        });
    }
}
