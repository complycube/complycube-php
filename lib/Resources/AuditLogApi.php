<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;

class AuditLogApi extends ApiResource
{
    const ENDPOINT = "auditLogs";

    use GetResource, ListResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\AuditLog");
    }
}
