<?php

namespace ComplyCube\Resources;

class AddressApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'addresses';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\UpdateResource;
    use \ComplyCube\ResourceActions\DeleteResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Address');
    }

    use \ComplyCube\ResourceActions\CreateResource {
        \ComplyCube\ResourceActions\CreateResource::create as traitCreate;
    }

    /**
     * Creates a new address.
     *
     * @param $clientId client to assign new address to.
     * @return Address
     */
    public function create(string $clientId, $address)
    {
        if (is_array($address)) {
            $address["clientId"] = $clientId;
        } else {
            $address->clientId = $clientId;
        }
        return $this->traitCreate($address);
    }

    use \ComplyCube\ResourceActions\ListResource {
        \ComplyCube\ResourceActions\ListResource::list as traitList;
    }

    /**
     * List out addresses belonging to client.
     *
     * @param $clientId client whose addresses to retrieve.
     * @return Address
     */
    public function list(string $clientId, $queryParams = [])
    {
        $queryParams['clientId'] = $clientId;
        return $this->traitList($queryParams);
    }
}
