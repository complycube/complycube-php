<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\SearchResource;

class CompanyApi extends ApiResource
{
    const ENDPOINT = "companies";

    use SearchResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\CompanyDetails");
    }
}
