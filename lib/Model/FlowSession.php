<?php

namespace ComplyCube\Model;

use stdClass;

class FlowSession extends Model
{
    public ?string $clientId;
    public ?array $checkTypes;
    public ?string $successUrl;
    public ?string $cancelUrl;
    public ?bool $enableMonitoring;
    public ?string $language;
    public ?string $theme;
    public ?string $redirectUrl;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->checkTypes = property_exists($response, "checkTypes")
            ? $response->checkTypes
            : null;
    }
}
