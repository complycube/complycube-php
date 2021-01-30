<?php

namespace ComplyCube\Resources;

class TokenApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'tokens';

    use \ComplyCube\ResourceActions\CreateResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Token');
    }

    public function generate(string $clientId, string $referrer): \ComplyCube\Model\Token
    {
        $request = ['clientId' => $clientId, 'referrer' => $referrer];
        return $this->create($request);
    }
}
