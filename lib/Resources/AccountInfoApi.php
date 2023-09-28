<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\GetDirectResource;

class AccountInfoApi extends ApiResource
{
    const ENDPOINT = "accountInfo";

    use GetDirectResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\AccountInfo");
    }
}
