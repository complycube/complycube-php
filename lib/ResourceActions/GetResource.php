<?php

namespace ComplyCube\ResourceActions;

trait GetResource
{
    public function get(string $id, $queryParams = [], $options = [])
    {
        return new $this->resourceClass(
            $this->apiClient
                ->get(
                    $this::ENDPOINT . "/" . $id,
                    array_merge($queryParams, $options)
                )
                ->getDecodedBody()
        );
    }
}
