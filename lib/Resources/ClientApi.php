<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\CreateResource;
use ComplyCube\ResourceActions\DeleteResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;
use ComplyCube\ResourceActions\UpdateResource;

class ClientApi extends ApiResource
{
    const ENDPOINT = "clients";

    use GetResource,
        CreateResource,
        UpdateResource,
        DeleteResource,
        ListResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Client");
    }
}
