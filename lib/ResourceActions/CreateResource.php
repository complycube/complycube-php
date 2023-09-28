<?php

namespace ComplyCube\ResourceActions;

trait CreateResource
{
    public function create($data, $options = [])
    {
        return new $this->resourceClass(
            $this->apiClient
                ->post($this::ENDPOINT, $options, $data)
                ->getDecodedBody()
        );
    }
}
