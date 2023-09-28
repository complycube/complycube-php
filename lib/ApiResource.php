<?php

namespace ComplyCube;

use ComplyCube\ApiClient;

abstract class ApiResource
{
    /** @var ApiClient $apiClient the ComplyCube API client to use*/
    protected ApiClient $apiClient;
    /** @var string $resourceClass the model class of returned resource */
    protected string $resourceClass;

    public function __construct(ApiClient $apiClient, string $resourceClass)
    {
        $this->apiClient = $apiClient;
        $this->resourceClass = $resourceClass;
    }
}
