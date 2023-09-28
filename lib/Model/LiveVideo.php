<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class LiveVideo extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?string $language;
    public ?array $challenges;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        if (property_exists($response, "challenges")) {
            foreach ($response->challenges as $challenge) {
                $this->challenges[] = new Challenge($challenge);
            }
        } else {
            $this->challenges = null;
        }
    }
}
