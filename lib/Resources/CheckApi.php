<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\Check;
use ComplyCube\Model\Validation;
use ComplyCube\ResourceActions\CreateResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;
use ComplyCube\ResourceActions\UpdateResource;

class CheckApi extends ApiResource
{
    const ENDPOINT = "checks";

    use GetResource, UpdateResource, ListResource;

    use CreateResource {
        CreateResource::create as traitCreate;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Check");
    }

    /**
     * Creates a new Check.
     *
     * @param string $clientId of the client.
     * @param mixed $check object/array of detail.
     * @return Check
     */
    public function create(string $clientId, $check): Check
    {
        if (is_array($check)) {
            $check["clientId"] = $clientId;
        } else {
            $check->clientId = $clientId;
        }
        return $this->traitCreate($check);
    }

    /**
     * Validates outcome of the specified check.
     *
     * @param string $checkId being validated.
     * @param mixed $validation details to be applied.
     * @return Validation
     */
    public function validate(string $checkId, $validation): Validation
    {
        $response = $this->apiClient->post(
            $this::ENDPOINT . "/" . $checkId . "/validate",
            [],
            $validation,
        );
        $validation = new Validation($response->getDecodedBody()->outcome);
        return $validation;
    }
}
