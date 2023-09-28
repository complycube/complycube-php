<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class Check extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?bool $enableMonitoring;
    public ?string $documentId;
    public ?string $addressId;
    public ?string $livePhotoId;
    public ?string $liveVideoId;
    public ?string $entityName;
    public ?string $type;
    public ?CheckOptions $options;
    public ?bool $clientConsent;
    public ?string $status;
    public $result;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->options = property_exists($response, "options")
            ? new CheckOptions($response->options)
            : null;

        $this->result = property_exists($response, "result")
            ? $response->result
            : null;
    }
}
