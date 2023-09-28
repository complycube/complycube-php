<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\Client;
use ComplyCube\Model\Document;
use ComplyCube\Model\Image;
use ComplyCube\Model\PersonDetails;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\DocumentApi
 */
class DocumentTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private ?Client $personClient;
    private ?Document $document;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $document = new Document();
        $document->type = "passport";
        $this->document = $document;
        $personDetails = new PersonDetails();
        $personDetails->firstName = "Richard";
        $personDetails->lastName = "Nixon";
        $newClient = new Client();
        $newClient->type = "person";
        $newClient->email = "john@doe.com";
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;
    }

    public function testCreatePersonForDocument(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals(
            $this->personClient->personDetails->firstName,
            $result->personDetails->firstName
        );
        $this->assertEquals(
            $this->personClient->personDetails->lastName,
            $result->personDetails->lastName
        );
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testCreateDocument($clientId): Document
    {
        $adoc = $this->document;
        $result = $this->complycube->documents()->create($clientId, $adoc);
        $this->assertEquals($adoc->type, $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result;
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testCreateDocumentInline($clientId)
    {
        $adoc = $this->document->jsonSerialize();
        $result = $this->complycube->documents()->create($clientId, $adoc);
        $this->assertIsArray($adoc);
        $this->assertEquals($adoc["type"], $result->type);
        $this->assertEquals($clientId, $result->clientId);
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testCreateDocumentWithMissingType($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $doc = new Document();
        $this->complycube->documents()->create($clientId, $doc);
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testCreateDocumentWithInvalidType($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $doc = new Document();
        $doc->type = "INVALIDTYPE";
        $this->complycube->documents()->create($clientId, $doc);
    }

    /**
     * @depends testCreateDocument
     */
    public function testGetDocument($document)
    {
        $retrievedDoc = $this->complycube->documents()->get($document->id);
        $this->assertEquals($document->id, $retrievedDoc->id);
    }

    public function testGetNonExistentDocument()
    {
        try {
            $this->complycube->documents()->get("nonexistentid");
        } catch (ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }

    /**
     * @depends testCreateDocument
     */
    public function testUpdateDocumentType($document)
    {
        $doc = new Document();
        $doc->type = "driving_license";
        $result = $this->complycube->documents()->update($document->id, $doc);
        $this->assertEquals($result->type, $doc->type);
    }

    /**
     * @depends testCreateDocument
     */
    public function testUpdateDocumentInvalidType($document)
    {
        $this->expectException(ComplyCubeClientException::class);
        $doc = new Document();
        $doc->type = "INVALIDTYPE";
        $result = $this->complycube->documents()->update($document->id, $doc);
        $this->assertEquals($result->type, $doc->type);
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocument($document): Image
    {
        $image = new Image();
        $image->fileName = "front.jpg";
        $image->data = file_get_contents(
            "./tests/fixtures/encoded-20200609153459.txt"
        );
        $result = $this->complycube
            ->documents()
            ->upload($document->id, "front", $image);
        $this->assertEquals($image->fileName, $result->fileName);
        $this->assertEquals("front", $result->documentSide);
        $this->assertEquals("image/jpg", $result->contentType);
        return $result;
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocumentInline($document): Image
    {
        $result = $this->complycube
            ->documents()
            ->upload($document->id, "back", [
                "fileName" => "back.jpg",
                "data" => file_get_contents(
                    "./tests/fixtures/encoded-20200609153459.txt"
                ),
            ]);
        $this->assertEquals("back.jpg", $result->fileName);
        $this->assertEquals("back", $result->documentSide);
        $this->assertEquals("image/jpg", $result->contentType);
        return $result;
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocumentInvalidSide($document)
    {
        $this->expectException(ComplyCubeClientException::class);
        $image = new Image();
        $image->fileName = "front.jpg";
        $image->data = file_get_contents(
            "./tests/fixtures/encoded-20200609153459.txt"
        );
        $this->complycube
            ->documents()
            ->upload($document->id, "INVALIDSIDE", $image);
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocumentNoFilename($document)
    {
        $this->expectException(ComplyCubeClientException::class);
        $image = new Image();
        $image->data = file_get_contents(
            "./tests/fixtures/encoded-20200609153459.txt"
        );
        $this->complycube->documents()->upload($document->id, "front", $image);
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocumentNoData($document)
    {
        $this->expectException(ComplyCubeClientException::class);
        $image = new Image();
        $image->fileName = "front.jpg";
        $this->complycube->documents()->upload($document->id, "front", $image);
    }

    /**
     * @depends testCreateDocument
     */
    public function testDownloadImageFromDocument($document)
    {
        $download = $this->complycube
            ->documents()
            ->download($document->id, "front");
        $this->assertEquals("front.jpg", $download->fileName);
    }

    /**
     * @depends testCreateDocument
     */
    public function testDownloadImageFromDocumentInvalidSide($document)
    {
        $download = $this->complycube
            ->documents()
            ->download($document->id, "INVALIDSIDE");
        $this->assertEquals("front.jpg", $download->fileName);
    }

    public function testDownloadImageFromDocumentNonExistentId()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube
            ->documents()
            ->download("nonexistentid", "INVALIDSIDE");
    }

    /**
     * @depends testCreateDocument
     * @depends testUploadImageToDocument
     */
    public function testDeleteImageFromDocument($document, $img)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube
            ->documents()
            ->deleteImage($document->id, $img->documentSide);
        $this->complycube
            ->documents()
            ->download($document->id, $img->documentSide);
    }

    public function testDeleteImageNonExistentImage()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->documents()->deleteImage("nonexistentid", "front");
    }

    public function testDeleteNonExistentDocument()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->documents()->delete("nonexistentid");
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testListDocuments($clientId)
    {
        $documents = $this->complycube->documents()->list($clientId);
        $this->assertGreaterThan(0, $documents->totalItems);
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testList2DocumentsOnly($clientId)
    {
        $this->testCreateDocument($clientId);
        $this->testCreateDocument($clientId);
        $documents = $this->complycube
            ->documents()
            ->list($clientId, ["page" => 1, "pageSize" => 2]);
        $this->assertEquals(2, iterator_count($documents));
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testFilterPassportDocumentsOnly($clientId)
    {
        $this->testCreateDocument($clientId);
        $documents = $this->complycube
            ->documents()
            ->list($clientId, ["type" => "passport"]);
        foreach ($documents as $doc) {
            $this->assertEquals("passport", $doc->type);
        }
    }
}
