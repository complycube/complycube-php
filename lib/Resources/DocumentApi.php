<?php

namespace ComplyCube\Resources;

use ComplyCube\Model\Image;

class DocumentApi extends \ComplyCube\ApiResource
{
    const ENDPOINT = 'documents';

    use \ComplyCube\ResourceActions\GetResource;
    use \ComplyCube\ResourceActions\CreateResource;
    use \ComplyCube\ResourceActions\UpdateResource;
    use \ComplyCube\ResourceActions\DeleteResource;

    public function __construct(\ComplyCube\ApiClient $apiClient)
    {
        parent::__construct($apiClient, '\ComplyCube\Model\Document');
    }

    use \ComplyCube\ResourceActions\CreateResource {
        \ComplyCube\ResourceActions\CreateResource::create as traitCreate;
    }

    /**
     * Creates a new Document.
     *
     * @param $clientId client to assign documents to.
     * @param $document document object/array of detail.
     * @return Document
     */
    public function create(string $clientId, $document)
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
     * @param $documentImage object/array of image detail.
     * @return Image
     */
    public function upload(string $id, string $side, $documentImage): Image
    {
        $response = $this->apiClient->post($this::ENDPOINT . '/' . $id . '/upload/' . $side, [], $documentImage);
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
        $response = $this->apiClient->get($this::ENDPOINT . '/' . $id . '/download/' . $side, []);
        $img = new Image();
        $img->load($response->getDecodedBody());
        return $img;
    }

    public function deleteImage(string $id, string $side): void
    {
        $this->apiClient->get($this::ENDPOINT . '/' . $id . '/' . $side, []);
    }

    use \ComplyCube\ResourceActions\ListResource {
        \ComplyCube\ResourceActions\ListResource::list as traitList;
    }

    public function list(string $clientId, $queryParams = [])
    {
        $queryParams['clientId'] = $clientId;
        return $this->traitList($queryParams);
    }
}
