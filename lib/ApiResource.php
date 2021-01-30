<?php

namespace ComplyCube;

abstract class ApiResource
{
    /** @var \ComplyCube\ApiClient $apiClient the ComplyCube API client to use*/
    protected \ComplyCube\ApiClient $apiClient;
    /** @var string $resourceClass the model class of returned resource */
    protected string $resourceClass;

    public function __construct(\ComplyCube\ApiClient $apiClient, string $resourceClass)
    {
        $this->apiClient = $apiClient;
        $this->resourceClass = $resourceClass;
    }
}
