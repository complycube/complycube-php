<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\Address;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\ResourceActions\CreateResource;
use ComplyCube\ResourceActions\DeleteResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;
use ComplyCube\ResourceActions\SearchResource;
use ComplyCube\ResourceActions\UpdateResource;

class AddressApi extends ApiResource
{
    const ENDPOINT = "addresses";

    use GetResource, UpdateResource, DeleteResource, SearchResource {
        GetResource::get insteadof SearchResource;
    }

    use CreateResource {
        CreateResource::create as traitCreate;
    }

    use ListResource {
        ListResource::list as traitList;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Address");
    }

    /**
     * Creates a new address.
     *
     * @param string $clientId client to assign new address to.
     * @param mixed $address address data
     * @return Address
     */
    public function create(string $clientId, $address): Address
    {
        if (is_array($address)) {
            $address["clientId"] = $clientId;
        } else {
            $address->clientId = $clientId;
        }
        return $this->traitCreate($address);
    }

    /**
     * List out addresses belonging to client.
     *
     * @param string $clientId client whose addresses to retrieve.
     * @param mixed $queryParams query parameters.
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
