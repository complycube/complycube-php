<?php

namespace ComplyCube\ResourceActions;

trait GetResource
{
    public function get(string $id, $queryParams = [], $options = [])
    {
        $response = $this->apiClient->get($this::ENDPOINT . '/' . $id, array_merge($queryParams, $options));
        $resource = new $this->resourceClass();
        $resource->load($response->getDecodedBody());
        return $resource;
    }
}
