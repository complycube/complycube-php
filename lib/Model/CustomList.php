<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class CustomList extends Model
{
    public ?string $id;
    public ?string $name;
    public ?string $description;
    public ?CustomListStats $stats;
    protected ?string $lastActionBy;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->stats = property_exists($response, "stats")
            ? new CustomListStats($response->stats)
            : null;
    }
}
