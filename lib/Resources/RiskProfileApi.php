<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\RiskProfile;
use ComplyCube\ResourceActions\GetResource;

class RiskProfileApi extends ApiResource
{
    const ENDPOINT = "clients";

    use GetResource {
        GetResource::get as traitGet;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\RiskProfile");
    }

    /**
     * @param string $id risk profile id
     * @return RiskProfile
     */

    public function get(string $id): RiskProfile
    {
        return $this->traitGet($id . "/riskProfile");
    }
}
