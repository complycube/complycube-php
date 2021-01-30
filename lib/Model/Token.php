<?php

namespace ComplyCube\Model;

use \stdClass;

class Token implements \JsonSerializable
{
    public ?string $token = null;

    public function load(stdClass $response)
    {
        $this->token = $response->token;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'token' => $this->token
        ], function ($value) {
            return ($value !== null);
        });
    }
}
