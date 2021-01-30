<?php

namespace ComplyCube\ResourceActions;

trait CreateResource
{
    /**
     * Create instance of resource
     *
     * @param $data array or object to be posted
     * @param array $options request options
     * @return stdClass
     */
    public function create($data, $options = [])
    {
        $response = $this->apiClient->post($this::ENDPOINT, $options, $data);
        $resource = new $this->resourceClass();
        $resource->load($response->getDecodedBody());
        return $resource;
    }
}
