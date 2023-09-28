<?php

namespace ComplyCube\Model;

use stdClass;

class AuditDiff extends Model
{
    public ?string $action;
    public ?array $path;
    public ?string $old;
    public $new;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->path = property_exists($response, "path")
            ? $response->path
            : null;

        $this->new = property_exists($response, "new") ? $response->new : null;
    }
}
