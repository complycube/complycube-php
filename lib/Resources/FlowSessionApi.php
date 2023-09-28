<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\FlowSession;
use ComplyCube\ResourceActions\CreateResource;

class FlowSessionApi extends ApiResource
{
    const ENDPOINT = "flow/sessions";

    use CreateResource {
        CreateResource::create as traitCreate;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\FlowSession");
    }

    /**
     * Creates a new Flow Session.
     *
     * @param mixed $flowSession session options to use.
     * @return FlowSession
     */
    public function createSession($flowSession): FlowSession
    {
        return $this->traitCreate($flowSession);
    }
}
