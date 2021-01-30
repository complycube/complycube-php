<?php

namespace ComplyCube\Resources;

class TeamMemberApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'teamMembers';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\TeamMember');
    }
}
