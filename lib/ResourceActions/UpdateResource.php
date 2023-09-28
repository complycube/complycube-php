<?php

namespace ComplyCube\ResourceActions;

trait UpdateResource
{
    public function update(string $id, $data, $options = [])
    {
        return new $this->resourceClass(
            $this->apiClient
                ->post($this::ENDPOINT . "/" . $id, $options, $data)
                ->getDecodedBody()
        );
    }
}
