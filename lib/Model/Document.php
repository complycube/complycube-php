<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class Document extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?string $type;
    public ?string $classification;
    public ?string $issuingCountry;
    public ?array $images;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        if (property_exists($response, "images")) {
            foreach ($response->images as $image) {
                $this->images[] = new Image($image);
            }
        } else {
            $this->images = null;
        }
    }
}
