<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class Event extends Model
{
    public ?string $id;
    public ?string $type;
    public ?string $resourceType;
    public $payload;
    protected ?Carbon $createdAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->payload = property_exists($response, "payload")
            ? $response->payload
            : null;
    }
}
