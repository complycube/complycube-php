<?php

namespace ComplyCube\ResourceActions;

trait ListResource
{
    public function list($queryParams = [], $options = []): \ComplyCube\Model\ComplyCubeCollection
    {
        $response = $this->apiClient->get($this::ENDPOINT, array_merge(['query' => $queryParams], $options));
        return new \ComplyCube\Model\ComplyCubeCollection($this->resourceClass, $response->getDecodedBody());
    }
}
