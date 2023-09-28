<?php

namespace ComplyCube\ResourceActions;

use ComplyCube\Model\ComplyCubeCollection;

trait ListResource
{
    public function list($queryParams = [], $options = []): ComplyCubeCollection
    {
        return new ComplyCubeCollection(
            $this->resourceClass,
            $this->apiClient
                ->get(
                    $this::ENDPOINT,
                    array_merge(["query" => $queryParams], $options)
                )
                ->getDecodedBody()
        );
    }
}
