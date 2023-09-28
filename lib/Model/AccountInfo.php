<?php

namespace ComplyCube\Model;

class AccountInfo extends Model
{
    public ?string $username;
    public ?string $plan;
    public ?int $remainingCredit;
}
