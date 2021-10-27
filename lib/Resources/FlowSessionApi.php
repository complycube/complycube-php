<?php

namespace ComplyCube\Resources;

class FlowSessionApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'flow/sessions';

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\FlowSession');
    }

    use \ComplyCube\ResourceActions\CreateResource {
        \ComplyCube\ResourceActions\CreateResource::create as traitCreate;
    }

    /**
     * Creates a new Flow Session.
     *
     * @param $flowSession session options to use.
     * @return FlowSession
     */
    public function createSession($flowSession)
    {
        return $this->traitCreate($flowSession);
    }
}
