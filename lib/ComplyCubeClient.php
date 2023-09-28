<?php

namespace ComplyCube;

use ComplyCube\ApiClient;
use ComplyCube\Resources\AccountInfoApi;
use ComplyCube\Resources\AddressApi;
use ComplyCube\Resources\AuditLogApi;
use ComplyCube\Resources\CheckApi;
use ComplyCube\Resources\ClientApi;
use ComplyCube\Resources\CompanyApi;
use ComplyCube\Resources\CustomListApi;
use ComplyCube\Resources\DocumentApi;
use ComplyCube\Resources\FlowSessionApi;
use ComplyCube\Resources\LivePhotoApi;
use ComplyCube\Resources\LiveVideoApi;
use ComplyCube\Resources\ReportApi;
use ComplyCube\Resources\RiskProfileApi;
use ComplyCube\Resources\TeamMemberApi;
use ComplyCube\Resources\TokenApi;
use ComplyCube\Resources\WebhookApi;

class ComplyCubeClient
{
    public ApiClient $apiClient;
    private ?ClientApi $clientApi;
    private ?AddressApi $addressApi;
    private ?CheckApi $checkApi;
    private ?DocumentApi $documentApi;
    private ?LivePhotoApi $livePhotoApi;
    private ?LiveVideoApi $liveVideoApi;
    private ?ReportApi $reportApi;
    private ?RiskProfileApi $riskProfileApi;
    private ?WebhookApi $webhookApi;
    private ?TokenApi $tokenApi;
    private ?TeamMemberApi $teamMemberApi;
    private ?AuditLogApi $auditLogApi;
    private ?AccountInfoApi $accountInfoApi;
    private ?FlowSessionApi $flowSessionApi;
    private ?CompanyApi $companyApi;
    private ?CustomListApi $customListApi;

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
    public function clients(): ClientApi
    {
        if (empty($this->clientApi)) {
            $this->clientApi = new ClientApi($this->apiClient);
        }
        return $this->clientApi;
    }

    /**
     * Address API allows you to create, retrieve, update, and delete your clients' addresses.
     * You can retrieve a specific address as well as a list of all your client's addresses.
     *
     * @return AddressApi
     */
    public function address(): AddressApi
    {
        if (empty($this->addressApi)) {
            $this->addressApi = new AddressApi($this->apiClient);
        }
        return $this->addressApi;
    }

    /**
     * Checks API allows you to create, update, validate, and retrieve checks.
     * You can retrieve a specific check as well as a list all your client's checks.
     *
     * @return CheckApi
     */
    public function checks(): CheckApi
    {
        if (empty($this->checkApi)) {
            $this->checkApi = new CheckApi($this->apiClient);
        }
        return $this->checkApi;
    }

    /**
     * Documents API allows you to create, update, retrieve, upload, and delete documents.
     * You can retrieve a specific document as well as a list of all your client's documents.
     *
     * @return DocumentApi
     */
    public function documents(): DocumentApi
    {
        if (empty($this->documentApi)) {
            $this->documentApi = new DocumentApi($this->apiClient);
        }
        return $this->documentApi;
    }

    /**
     * Live photos API allows you to upload, retrieve, download, and delete live photos.
     * You can retrieve a specific live photo as well as a list all your client's live photos.
     *
     * @return LivePhotoApi
     */
    public function livephotos(): LivePhotoApi
    {
        if (empty($this->livePhotoApi)) {
            $this->livePhotoApi = new LivePhotoApi($this->apiClient);
        }
        return $this->livePhotoApi;
    }

    /**
     * Live videos API allows you to upload, retrieve, download, and delete live photos.
     * You can retrieve a specific live photo as well as a list all your client's live photos.
     *
     * @return LiveVideoApi
     */
    public function livevideos(): LiveVideoApi
    {
        if (empty($this->liveVideoApi)) {
            $this->liveVideoApi = new LiveVideoApi($this->apiClient);
        }
        return $this->liveVideoApi;
    }

    /**
     * Report API allows you to create a PDF file extract for a given client or check.
     * The report represents a snapshot instance of the client or check at the time of generation.
     *
     * @return ReportApi
     */
    public function reports(): ReportApi
    {
        if (empty($this->reportApi)) {
            $this->reportApi = new ReportApi($this->apiClient);
        }
        return $this->reportApi;
    }

    /**
     * Risk Profile provides you with an AML risk score for a given client.
     * It facilitates a risk-based framework for Client Due Diligence (CDD) and Enhanced Due Diligence (EDD).
     * Furthermore, the risk profile will assist you in shaping your ongoing client relationship.
     *
     * @return RiskProfileApi
     */
    public function riskProfiles(): RiskProfileApi
    {
        if (empty($this->riskProfileApi)) {
            $this->riskProfileApi = new RiskProfileApi($this->apiClient);
        }
        return $this->riskProfileApi;
    }

    /**
     * Configure webhook endpoints via the API to be notified about events that happen in your
     * ComplyCube account and related resources.
     *
     * @return WebhookApi
     */
    public function webhooks(): WebhookApi
    {
        if (empty($this->webhookApi)) {
            $this->webhookApi = new WebhookApi($this->apiClient);
        }
        return $this->webhookApi;
    }

    /**
     * Tokens enable clients to send personal data to ComplyCube via our SDKs. They are JWTs.
     * Each token is confined to one client and expire after 60 minutes, so you can safely use
     * them in the frontend of your application.
     *
     * @return TokenApi
     */
    public function tokens(): TokenApi
    {
        if (empty($this->tokenApi)) {
            $this->tokenApi = new TokenApi($this->apiClient);
        }
        return $this->tokenApi;
    }

    /**
     * Team member API provides information on your team members.
     * You can get, filter and list your team members through the API.
     *
     * @return TeamMemberApi
     */
    public function teamMembers(): TeamMemberApi
    {
        if (empty($this->teamMemberApi)) {
            $this->teamMemberApi = new TeamMemberApi($this->apiClient);
        }
        return $this->teamMemberApi;
    }

    /**
     * Audit Log API allows you to retrieve audit logs for a given client, action, resource, or trigger.
     *
     * @return AuditLogApi
     */
    public function auditLogs(): AuditLogApi
    {
        if (empty($this->auditLogApi)) {
            $this->auditLogApi = new AuditLogApi($this->apiClient);
        }
        return $this->auditLogApi;
    }

    /**
     * Allows you to retrieve a list of all Sanctions and Watchlists available as part of a Screening Check.
     *
     * @return iterable
     */
    public function screeningLists(): iterable
    {
        $response = $this->apiClient->get("static/screeningLists");
        return json_decode($response->getBody(), true);
    }

    /**
     * Allows you to retrieve a list of all documents supported by our Document Checks service.
     *
     * @return iterable
     */
    public function supportedDocuments(): iterable
    {
        $response = $this->apiClient->get("static/supportedDocuments");
        return json_decode($response->getBody(), true);
    }

    /**
     * Account Info API allows you to get account info around usage and credits.
     *
     * @return AccountInfoApi
     */
    public function accountInfo(): AccountInfoApi
    {
        if (empty($this->accountInfoApi)) {
            $this->accountInfoApi = new AccountInfoApi($this->apiClient);
        }
        return $this->accountInfoApi;
    }

    /**
     * Flow API lets you create a unique ComplyCube URL address to redirect your clients
     *
     * @return FlowSessionApi
     */
    public function flow(): FlowSessionApi
    {
        if (empty($this->flowSessionApi)) {
            $this->flowSessionApi = new FlowSessionApi($this->apiClient);
        }
        return $this->flowSessionApi;
    }

    /**
     * Company API allows you to retrieve and search for companies details.
     * You can retrieve a specific company's details as well as a list of all of your companies'.
     *
     * @return CompanyApi
     */
    public function companies(): CompanyApi
    {
        if (empty($this->companyApi)) {
            $this->companyApi = new CompanyApi($this->apiClient);
        }
        return $this->companyApi;
    }

    /**
     * Company API allows you to retrieve, list and update your custom lists.
     * You can retrieve a specific custom list's details as well as a list of all of your custom lists'.
     *
     * @return CustomListApi
     */
    public function customLists(): CustomListApi
    {
        if (empty($this->customListApi)) {
            $this->customListApi = new CustomListApi($this->apiClient);
        }
        return $this->customListApi;
    }
}
