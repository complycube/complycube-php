<?php

namespace ComplyCube\Resources;

class RiskProfileApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'clients';

    use \ComplyCube\ResourceActions\GetResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\RiskProfile');
    }

    use \ComplyCube\ResourceActions\GetResource {
        \ComplyCube\ResourceActions\GetResource::get as traitGet;
    }

    public function get(string $id)
    {
        return $this->traitGet($id.'/riskProfile');
    }
}
