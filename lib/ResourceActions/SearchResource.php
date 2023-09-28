<?php

namespace ComplyCube\ResourceActions;

use ComplyCube\Model\ComplyCubeCollection;

trait SearchResource
{
    public function search($data, $options = []): ComplyCubeCollection
    {
        return new ComplyCubeCollection(
            $this->resourceClass,
            $this->apiClient
                ->post("lookup" . "/" . $this::ENDPOINT, $options, $data)
                ->getDecodedBody()
        );
    }

    public function get(string $id, $queryParams = [], $options = [])
    {
        return new $this->resourceClass(
            $this->apiClient
                ->get(
                    "lookup" . "/" . $this::ENDPOINT . "/" . $id,
                    array_merge($queryParams, $options)
                )
                ->getDecodedBody()
        );
    }
}
