<?php

namespace ComplyCube\Resources;

class AccountInfoApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'accountInfo';

    use \ComplyCube\ResourceActions\GetDirectResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\AccountInfo');
    }
}
