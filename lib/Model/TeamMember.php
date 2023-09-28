<?php

namespace ComplyCube\Model;

use Carbon\Carbon;

class TeamMember extends Model
{
    public ?string $id;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $role;
    protected ?Carbon $createdAt;
}
