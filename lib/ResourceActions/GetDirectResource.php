<?php

namespace ComplyCube\ResourceActions;

trait GetDirectResource
{
    public function get($queryParams = [], $options = [])
    {
        $response = $this->apiClient->get($this::ENDPOINT, array_merge($queryParams, $options));
        $resource = new $this->resourceClass();
        $resource->load($response->getDecodedBody());
        return $resource;
    }
}
