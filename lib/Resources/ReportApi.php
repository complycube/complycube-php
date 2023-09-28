<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\Report;
use ComplyCube\ResourceActions\GetResource;

class ReportApi extends ApiResource
{
    const ENDPOINT = "reports";

    use GetResource;

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Report");
    }

    /**
     * Generates a client/check PDF report.
     *
     * @param array $queryParams clientId or checkId to be used.
     * @return Report
     */
    public function generate(array $queryParams): Report
    {
        return $this->get("", ["query" => $queryParams]);
    }
}
