<?php

namespace ComplyCube\Model;

use \stdClass;

class Webhook implements \JsonSerializable
{
    public ?string $id = null;
    public ?bool $enabled = null;
    public ?string $description = null;
    public ?string $url = null;
    public ?string $secret = null;
    public $events = null;

    public function load(stdClass $response)
    {
        $this->id = isset($response->id) ? $response->id : null;
        $this->description = isset($response->description) ? $response->description : null;
        $this->url = isset($response->url) ? $response->url : null;
        $this->secret = isset($response->secret) ? $response->secret : null;
        $this->events = isset($response->events) ? $response->events : null;
        $this->enabled = isset($response->enabled) ? $response->enabled : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id' => $this->id,
            'description' => $this->description,
            'url' => $this->url,
            'events' => $this->events,
            'secret' => $this->secret,
            'enabled' => $this->enabled
        ], function ($value) {
            return ($value !== null);
        });
    }
}
