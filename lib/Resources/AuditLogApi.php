<?php

namespace ComplyCube\Resources;

class AuditLogApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'auditLogs';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\AuditLog');
    }
}
