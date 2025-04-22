<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\Token;
use ComplyCube\ResourceActions\CreateResource;

class TokenApi extends ApiResource
{
    const ENDPOINT = "tokens";

    use CreateResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Token");
    }

    public function generate(string $clientId, string $referrer): Token
    {
        return $this->create([
            "clientId" => $clientId,
            preg_match(
                "/(^([A-Za-z0-9]+\.)+[A-Za-z0-9]{2,}$)|(^[a-zA-Z][a-zA-Z0-9_]*(\.[a-zA-Z][a-zA-Z0-9_]*)*$)/",
                $referrer
            )
                ? "appId"
                : "referrer" => $referrer,
        ]);
    }

    public function generateAppToken(string $clientId, string $appId): \ComplyCube\Model\Token
    {
        $request = ['clientId' => $clientId, 'appId' => $appId];
        return $this->create($request);
    }
}
