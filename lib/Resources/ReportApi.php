<?php

namespace ComplyCube\Resources;

use ComplyCube\Model\Report;

class ReportApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'reports';

    use \ComplyCube\ResourceActions\GetResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Report');
    }

    /**
     * Generates a client/check PDF report.
     *
     * @param array $queryParams clientId or checkId to be used.
     * @return Report
     */
    public function generate(array $queryParams): Report
    {
        return $this->get('', ['query'=> $queryParams]);
    }
}
