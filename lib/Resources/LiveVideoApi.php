<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\ResourceActions\DeleteResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;

class LiveVideoApi extends ApiResource
{
    const ENDPOINT = "liveVideos";

    use GetResource, DeleteResource;

    use ListResource {
        ListResource::list as traitList;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\LiveVideo");
    }

    /**
     * List all existing live photos for a given client.
     *
     * @param string $clientId of the client.
     * @param mixed $queryParams for pagination and filtering.
     * @return ComplyCubeCollection
     */
    public function list(
        string $clientId,
        $queryParams = []
    ): ComplyCubeCollection {
        $queryParams["clientId"] = $clientId;
        return $this->traitList($queryParams);
    }
}
