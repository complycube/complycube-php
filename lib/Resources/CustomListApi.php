<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;

class CustomListApi extends ApiResource
{
    const ENDPOINT = "customLists";

    use GetResource, ListResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\CustomList");
    }

    /**
     * Adds an entity to a custom list.
     *
     * @param string $id the ID of the custom list.
     * @param mixed $data entity data
     * @return void
     */
    public function add(string $id, $data): void
    {
        $this->apiClient->post(
            $this::ENDPOINT . "/" . $id . "/records",
            [],
            $data
        );
    }
}
