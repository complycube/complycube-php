<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;

class TeamMemberApi extends ApiResource
{
    const ENDPOINT = "teamMembers";

    use GetResource, ListResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\TeamMember");
    }
}
