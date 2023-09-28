<?php

namespace ComplyCube\Model;

class Validation extends Model
{
    public ?string $outcome;
    public ?string $matchId;
    public ?string $comment;

    public function __construct(string $outcome)
    {
        $this->load((object) compact("outcome"));
    }
}
