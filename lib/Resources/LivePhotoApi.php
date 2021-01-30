<?php

namespace ComplyCube\Resources;

use ComplyCube\Model\Image;

class LivePhotoApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'livePhotos';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\CreateResource;
    use \ComplyCube\ResourceActions\UpdateResource;
    use \ComplyCube\ResourceActions\DeleteResource;
    use \ComplyCube\ResourceActions\ListResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Image');
    }

    use \ComplyCube\ResourceActions\CreateResource {
        \ComplyCube\ResourceActions\CreateResource::create as traitCreate;
    }
    
    /**
     * Upload livephoto of a client.
     *
     * @param string $clientId of the client being uploaded.
     * @param $img object/array of image detail.
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
        return $this->get($id . '/download');
    }

    use \ComplyCube\ResourceActions\ListResource {
        \ComplyCube\ResourceActions\ListResource::list as traitList;
    }

    /**
     * List all existing live photos for a given client.
     *
     * @param string $clientId of the client.
     * @param array $queryParams for pagination and filtering.
     * @return void
     */
    public function list(string $clientId, $queryParams = [])
    {
        $queryParams['clientId'] = $clientId;
        return $this->traitList($queryParams);
    }
}
