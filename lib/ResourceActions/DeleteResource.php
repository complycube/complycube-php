<?php

namespace ComplyCube\ResourceActions;

trait DeleteResource
{
    public function delete(string $id, $options = []): void
    {
        $this->apiClient->delete($this::ENDPOINT . "/" . $id, $options);
    }
}
