<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class AuditLog extends Model
{
    public ?string $id;
    public ?string $memberId;
    public ?string $resourceType;
    public ?string $resourceId;
    public ?string $clientId;
    public ?string $trigger;
    public ?string $action;
    public ?array $diff;
    public ?Carbon $createdAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        if (isset($response->diff)) {
            foreach ($response->diff as $aDiff) {
                $this->diff[] = new AuditDiff($aDiff);
            }
        } else {
            $this->diff = null;
        }
    }
}
