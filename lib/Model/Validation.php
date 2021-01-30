<?php

namespace ComplyCube\Model;

use \stdClass;

class Validation implements \JsonSerializable
{
    public string $outcome;
    public ?string $matchId = null;
    public ?string $comment = null;

    public function __construct(string $outcome)
    {
        $this->outcome = $outcome;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'outcome' => $this->outcome,
            'matchId' => $this->matchId,
            'comment' => $this->comment
        ], function ($value) {
            return ($value !== null);
        });
    }
}
