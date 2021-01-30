<?php

namespace ComplyCube\Resources;

use \ComplyCube\Model\Validation;

class CheckApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'checks';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\CreateResource;
    use \ComplyCube\ResourceActions\UpdateResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Check');
    }

    use \ComplyCube\ResourceActions\CreateResource {
        \ComplyCube\ResourceActions\CreateResource::create as traitCreate;
    }

    /**
     * Creates a new Check.
     *
     * @param $clientId of the client.
     * @param $document document object/array of detail.
     * @return Check
     */
    public function create(string $clientId, $check)
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
     * @param $validation details to be applied.
     * @return Validation
     */
    public function validate(string $checkId, $validation): Validation
    {
        $response = $this->apiClient->post($this::ENDPOINT . '/' . $checkId .'/validate', [], $validation);
        $validation = new Validation($response->getDecodedBody()->outcome);
        return $validation;
    }
}
