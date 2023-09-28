<?php

namespace ComplyCube\Resources;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\Document;
use ComplyCube\Model\Image;
use ComplyCube\ResourceActions\CreateResource;
use ComplyCube\ResourceActions\DeleteResource;
use ComplyCube\ResourceActions\GetResource;
use ComplyCube\ResourceActions\ListResource;
use ComplyCube\ResourceActions\UpdateResource;

class DocumentApi extends ApiResource
{
    const ENDPOINT = "documents";

    use GetResource, DeleteResource, UpdateResource;

    use CreateResource {
        CreateResource::create as traitCreate;
    }

    use ListResource {
        ListResource::list as traitList;
    }

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, "\ComplyCube\Model\Document");
    }

    /**
     * Creates a new Document.
     *
     * @param string $clientId client to assign documents to.
     * @param mixed $document document object/array of detail.
     * @return Document
     */
    public function create(string $clientId, $document): Document
    {
        if (is_array($document)) {
            $document["clientId"] = $clientId;
        } else {
            $document->clientId = $clientId;
        }
        return $this->traitCreate($document);
    }

    /**
     * Upload a side of a client document.
     *
     * @param string $id of the document to upload to.
     * @param string $side of the document being uploaded.
     * @param mixed $documentImage object/array of image detail.
     * @return Image
     */
    public function upload(string $id, string $side, $documentImage): Image
    {
        $response = $this->apiClient->post(
            $this::ENDPOINT . "/" . $id . "/upload/" . $side,
            [],
            $documentImage,
        );
        $img = new Image();
        $img->load($response->getDecodedBody());
        return $img;
    }

    /**
     * Download a side of a client document.
     *
     * @param string $id of the document being downloaded.
     * @param string $side of the document being downloaded.
     * @return Image
     */
    public function download(string $id, string $side): Image
    {
        $response = $this->apiClient->get(
            $this::ENDPOINT . "/" . $id . "/download/" . $side,
            [],
        );
        $img = new Image();
        $img->load($response->getDecodedBody());
        return $img;
    }

    /**
     *
     *
     * @param string $id
     * @param string $side
     * @return void
     */

    public function deleteImage(string $id, string $side): void
    {
        $this->apiClient->get($this::ENDPOINT . "/" . $id . "/" . $side, []);
    }

    /**
     * List out documents belonging to client.
     *
     * @param string $clientId client whose documents to retrieve.
     * @param mixed $queryParams query parameters.
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
