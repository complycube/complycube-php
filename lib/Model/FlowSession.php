<?php

namespace ComplyCube\Model;

use \stdClass;

class FlowSession implements \JsonSerializable
{
    public ?string $clientId = null;
    public $checkTypes = null;
    public ?string $successUrl = null;
    public ?string $cancelUrl = null;
    public ?string $theme = null;
    public ?string $language = null;
    public ?bool $enableMonitoring = null;
    public ?string $redirectUrl = null;

    public function load(stdClass $response)
    {
        $this->clientId = isset($response->clientId) ?  $response->clientId : null;
        $this->checkTypes = isset($response->checkTypes) ?  $response->checkTypes : null;
        $this->successUrl = isset($response->successUrl) ?  $response->successUrl : null;
        $this->cancelUrl = isset($response->cancelUrl) ?  $response->cancelUrl : null;
        $this->theme = isset($response->theme) ?  $response->theme : null;
        $this->language = isset($response->language) ?  $response->language : null;
        $this->enableMonitoring = isset($response->enableMonitoring) ?  $response->enableMonitoring : null;
        $this->redirectUrl = isset($response->redirectUrl) ?  $response->redirectUrl : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'clientId' => $this->clientId,
            'checkTypes' => $this->checkTypes,
            'successUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl,
            'theme' => $this->theme,
            'language' => $this->language,
            'enableMonitoring' => $this->enableMonitoring,
            'redirectUrl' => $this->redirectUrl
        ], function ($value) {
            return ($value !== null);
        });
    }
}
