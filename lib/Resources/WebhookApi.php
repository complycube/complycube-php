<?php

namespace ComplyCube\Resources;

class WebhookApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'webhooks';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\CreateResource;
    use \ComplyCube\ResourceActions\UpdateResource;
    use \ComplyCube\ResourceActions\DeleteResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Webhook');
    }
}
