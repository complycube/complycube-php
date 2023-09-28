<?php

namespace ComplyCube\Model;

use Carbon\Carbon;

class Image extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?string $fileName;
    public ?bool $performLivenessCheck;
    public ?string $documentSide;
    public ?string $downloadLink;
    public ?string $contentType;
    public ?string $data;
    public ?int $size;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;
}
