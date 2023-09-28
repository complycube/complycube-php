<?php

namespace ComplyCube\Model;

use Carbon\Carbon;

class Address extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?string $type;
    public ?string $propertyNumber;
    public ?string $buildingName;
    public ?string $line;
    public ?string $city;
    public ?string $state;
    public ?string $postalCode;
    public ?string $country;
    public ?string $fromDate;
    public ?string $toDate;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;
}
