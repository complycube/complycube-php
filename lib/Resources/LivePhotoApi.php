<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\Image;
use ComplyCube\ResourceActions\CreateResource;
use ComplyCube\ResourceActions\DeleteResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;
use ComplyCube\ResourceActions\UpdateResource;

class LivePhotoApi extends ApiResource
{
    const ENDPOINT = "livePhotos";

    use GetResource, UpdateResource, DeleteResource;

    use CreateResource {
        CreateResource::create as traitCreate;
    }

    use ListResource {
        ListResource::list as traitList;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Image");
    }

    /**
     * Upload livephoto of a client.
     *
     * @param string $clientId of the client being uploaded.
     * @param mixed $img object/array of image detail.
     * @return Image
     */
    public function upload(string $clientId, $img): Image
    {
        if (is_array($img)) {
            $img["clientId"] = $clientId;
        } else {
            $img->clientId = $clientId;
        }
        return $this->traitCreate($img);
    }

    /**
     * Download livephoto of a client.
     *
     * @param string $id of the livephoto being downloaded.
     * @return Image
     */
    public function download(string $id): Image
    {
        return $this->get($id . "/download");
    }

    /**
     * List all existing live photos for a given client.
     *
     * @param string $clientId of the client.
     * @param mixed $queryParams for pagination and filtering.
     * @return ComplyCubeCollection
     */
    public function list(
        string $clientId,
        $queryParams = []
    ): ComplyCubeCollection {
        $queryParams["clientId"] = $clientId;
        return $this->traitList($queryParams);
    }
}
