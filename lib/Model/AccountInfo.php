<?php

namespace ComplyCube\Model;

class AccountInfo implements \JsonSerializable
{
    public string $username;
    public string $plan;
    public ?string $remainingCredit = null;

    public function load(\stdClass $response)
    {
        $this->username = $response->username;
        $this->plan = $response->plan;
        $this->remainingCredit = isset($response->remainingCredit) ?  $response->remainingCredit : null;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'username' => $this->username,
            'plan' => $this->plan,
            'remainingCredit' => $this->remainingCredit
        ], function ($value) {
            return ($value !== null);
        });
    }
}
