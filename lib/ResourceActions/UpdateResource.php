<?php

namespace ComplyCube\ResourceActions;

trait UpdateResource
{
    public function update(string $id, $data, $options = [])
    {
        $response = $this->apiClient->post($this::ENDPOINT . '/' . $id, $options, $data);
        $resource = new $this->resourceClass();
        $resource->load($response->getDecodedBody());
        return $resource;
    }
}
