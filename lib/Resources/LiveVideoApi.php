<?php

namespace ComplyCube\Resources;

use ComplyCube\Model\LiveVideo;

class LiveVideoApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'liveVideos';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\DeleteResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\LiveVideo');
    }

    use \ComplyCube\ResourceActions\ListResource {
        \ComplyCube\ResourceActions\ListResource::list as traitList;
    }

    /**
     * List all existing live photos for a given client.
     *
     * @param string $clientId of the client.
     * @param array $queryParams for pagination and filtering.
     * @return void
     */
    public function list(string $clientId, $queryParams = [])
    {
        $queryParams['clientId'] = $clientId;
        return $this->traitList($queryParams);
    }
}
