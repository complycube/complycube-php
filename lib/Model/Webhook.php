<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class Webhook extends Model
{
    public ?string $id;
    public ?string $description;
    public ?string $url;
    public ?bool $enabled;
    public ?array $events;
    public ?string $secret;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->events = property_exists($response, "events")
            ? $response->events
            : null;
    }
}
