<?php

namespace ComplyCube\ResourceActions;

trait GetDirectResource
{
    public function get($queryParams = [], $options = [])
    {
        return new $this->resourceClass(
            $this->apiClient
                ->get($this::ENDPOINT, array_merge($queryParams, $options))
                ->getDecodedBody()
        );
    }
}
