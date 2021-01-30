<?php

namespace ComplyCube;

use ComplyCube\ApiClient;

class ComplyCubeClient
{
    public $apiClient;
    private $clientApi;
    private $addressApi;
    private $checkApi;
    private $documentApi;
    private $livePhotoApi;
    private $riskProfileApi;
    private $webhookApi;
    private $tokenApi;
    private $teamMemberApi;
    private $auditLogApi;
    
    /**
     * Create a ComplyCubeClient API Client Instance for the provided
     * API key.
     */
    public function __construct(string $apiKey, $maxRetries = 0)
    {
        $this->apiClient = new ApiClient($apiKey, $maxRetries);
    }

    /**
     * Client API allows you to create, retrieve, update, and delete your clients.
     * You can retrieve a specific clients as well as a list of all your clients.
     *
     * @return ClientApi
     */
    public function clients() : \ComplyCube\Resources\ClientApi
    {
        if (empty($this->clientApi)) {
            $this->clientApi = new \ComplyCube\Resources\ClientApi($this->apiClient);
        }
        return $this->clientApi;
    }

    /**
     * Address API allows you to create, retrieve, update, and delete your clients' addresses.
     * You can retrieve a specific address as well as a list of all your client's addresses.
     *
     * @return \ComplyCube\Resources\AddressApi
     */
    public function address(): \ComplyCube\Resources\AddressApi
    {
        if (empty($this->addressApi)) {
            $this->addressApi = new \ComplyCube\Resources\AddressApi($this->apiClient);
        }
        return $this->addressApi;
    }

    /**
     * Checks API allows you to create, update, validate, and retrieve checks.
     * You can retrieve a specific check as well as a list all your client's checks.
     *
     * @return ComplyCube\Resources\CheckApi
     */
    public function checks() : \ComplyCube\Resources\CheckApi
    {
        if (empty($this->checkApi)) {
            $this->checkApi = new \ComplyCube\Resources\CheckApi($this->apiClient);
        }
        return $this->checkApi;
    }

    /**
     * Documents API allows you to create, update, retrieve, upload, and delete documents.
     * You can retrieve a specific document as well as a list of all your client's documents.
     *
     * @return \ComplyCube\Resources\DocumentApi
     */
    public function documents() : \ComplyCube\Resources\DocumentApi
    {
        if (empty($this->documentApi)) {
            $this->documentApi = new \ComplyCube\Resources\DocumentApi($this->apiClient);
        }
        return $this->documentApi;
    }

    /**
     * Live photos API allows you to upload, retrieve, download, and delete live photos.
     * You can retrieve a specific live photo as well as a list all your client's live photos.
     *
     * @return \ComplyCube\Resources\LivePhotoApi
     */
    public function livephotos() : \ComplyCube\Resources\LivePhotoApi
    {
        if (empty($this->livePhotoApi)) {
            $this->livePhotoApi = new \ComplyCube\Resources\LivePhotoApi($this->apiClient);
        }
        return $this->livePhotoApi;
    }

    /**
     * Report API allows you to create a PDF file extract for a given client or check.
     * The report represents a snapshot instance of the client or check at the time of generation.
     *
     * @return \ComplyCube\Resources\ReportApi
     */
    public function reports() : \ComplyCube\Resources\ReportApi
    {
        if (empty($this->reportApi)) {
            $this->reportApi = new \ComplyCube\Resources\ReportApi($this->apiClient);
        }
        return $this->reportApi;
    }

    /**
     * Risk Profile provides you with an AML risk score for a given client.
     * It facilitates a risk-based framework for Client Due Diligence (CDD) and Enhanced Due Diligence (EDD).
     * Furthermore, the risk profile will assist you in shaping your ongoing client relationship.
     *
     * @return \ComplyCube\Resources\RiskProfileApi
     */
    public function riskProfiles() : \ComplyCube\Resources\RiskProfileApi
    {
        if (empty($this->riskprofileApi)) {
            $this->riskprofileApi = new \ComplyCube\Resources\RiskProfileApi($this->apiClient);
        }
        return $this->riskprofileApi;
    }

    /**
     * Configure webhook endpoints via the API to be notified about events that happen in your
     * ComplyCube account and related resources.
     *
     * @return \ComplyCube\Resources\WebhookApi
     */
    public function webhooks() : \ComplyCube\Resources\WebhookApi
    {
        if (empty($this->webhookApi)) {
            $this->webhookApi = new \ComplyCube\Resources\WebhookApi($this->apiClient);
        }
        return $this->webhookApi;
    }

    /**
     * Tokens enable clients to send personal data to ComplyCube via our SDKs. They are JWTs.
     * Each token is confined to one client and expire after 60 minutes, so you can safely use
     * them in the frontend of your application.
     *
     * @return \ComplyCube\Resources\TokenApi
     */
    public function tokens() : \ComplyCube\Resources\TokenApi
    {
        if (empty($this->tokenApi)) {
            $this->tokenApi = new \ComplyCube\Resources\TokenApi($this->apiClient);
        }
        return $this->tokenApi;
    }

    /**
     * Team member API provides information on your team members.
     * You can get, filter and list your team members through the API.
     *
     * @return \ComplyCube\Resources\TeamMemberApi
     */
    public function teamMembers() : \ComplyCube\Resources\TeamMemberApi
    {
        if (empty($this->teamMemberApi)) {
            $this->teamMemberApi = new \ComplyCube\Resources\TeamMemberApi($this->apiClient);
        }
        return $this->teamMemberApi;
    }

    /**
     * Audit Log API allows you to retrieve audit logs for a given client, action, resource, or trigger.
     *
     * @return \ComplyCube\Resources\AuditLogApi
     */
    public function auditLogs() : \ComplyCube\Resources\AuditLogApi
    {
        if (empty($this->auditLogApi)) {
            $this->auditLogApi = new \ComplyCube\Resources\AuditLogApi($this->apiClient);
        }
        return $this->auditLogApi;
    }
}
