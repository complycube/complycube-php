<?php

namespace ComplyCube\Model;

use stdClass;

class Challenge extends Model
{
    public ?string $type;
    public ?array $value;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->value = property_exists($response, "value")
            ? $response->value
            : null;
    }
}
