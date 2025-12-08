<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;

class WorkflowSessionApi extends ApiResource
{
    const ENDPOINT = "workflowSessions";

    use GetResource,
        ListResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\WorkflowSession");
    }

    /**
     * Complete the workflow session.
     *
     * @param string $workflowSessionId being completed.
     */
    public function complete(string $workflowSessionId)
    {
        $response = $this->apiClient->post(
            $this::ENDPOINT . "/" . $workflowSessionId . "/complete",
            []
        );
        return $response;
    }
}
