<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Image;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\Document;

/**
 * @covers \ComplyCube\Resources\DocumentApi
 */
class DocumentTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;
    private $document;
    private $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $document = new Document();
        $document->type = 'passport';
        $this->document = $document;
        $personDetails = new PersonDetails();
        $personDetails->firstName = 'Richard';
        $personDetails->lastName = 'Nixon';
        $newClient = new Client();
        $newClient->type = 'person';
        $newClient->email = 'john@doe.com';
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;
    }

    public function testCreatePersonForDocument(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->personDetails->firstName, $result->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $result->personDetails->lastName);
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
    public function testCreateDocumentWithMissingType($clientId)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $doc = new Document();
        $result = $this->complycube->documents()->create($clientId, $doc);
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testCreateDocumentWithInvalidType($clientId)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $doc = new Document();
        $doc->type = 'INVALIDTYPE';
        $result = $this->complycube->documents()->create($clientId, $doc);
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
            $retrievedClient = $this->complycube->documents()->get('nonexistentid');
        } catch (\ComplyCube\Exception\ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }

    /**
    * @depends testCreateDocument
    */
    public function testUpdateDocumentType($document)
    {
        $doc = new Document();
        $doc->type = 'driving_license';
        $result = $this->complycube->documents()->update($document->id, $doc);
        $this->assertEquals($result->type, $doc->type);
    }

    /**
    * @depends testCreateDocument
    */
    public function testUpdateDocumentInvalidType($document)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $doc = new Document();
        $doc->type = 'INVALIDTYPE';
        $result = $this->complycube->documents()->update($document->id, $doc);
        $this->assertEquals($result->type, $doc->type);
    }

    /**
    * @depends testCreateDocument
    */
    public function testUploadImageToDocument($document): Image
    {
        $image = new Image();
        $image->fileName = 'front.jpg';
        $image->data = file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r");
        $result = $this->complycube->documents()->upload($document->id, 'front', $image);
        $this->assertEquals($image->fileName, $result->fileName);
        $this->assertEquals('front', $result->documentSide);
        $this->assertEquals('image/jpg', $result->contentType);
        return $result;
    }

    /**
    * @depends testCreateDocument
    */
    public function testUploadImageToDocumentInline($document): Image
    {
        $result = $this->complycube->documents()->upload(
            $document->id,
            'back',
            ['fileName' => 'back.jpg',
            'data' => file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r")]
        );
        $this->assertEquals('back.jpg', $result->fileName);
        $this->assertEquals('back', $result->documentSide);
        $this->assertEquals('image/jpg', $result->contentType);
        return $result;
    }

    /**
    * @depends testCreateDocument
    */
    public function testUploadImageToDocumentInvalidSide($document)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $image = new Image();
        $image->fileName = 'front.jpg';
        $image->data = file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r");
        $result = $this->complycube->documents()->upload($document->id, 'INVALIDSIDE', $image);
    }

    /**
    * @depends testCreateDocument
    */
    public function testUploadImageToDocumentNoFilename($document)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $image = new Image();
        $image->data = file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r");
        $result = $this->complycube->documents()->upload($document->id, 'front', $image);
    }

    /**
    * @depends testCreateDocument
    */
    public function testUploadImageToDocumentNoData($document)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $image = new Image();
        $image->fileName = 'front.jpg';
        $result = $this->complycube->documents()->upload($document->id, 'front', $image);
    }

    /**
    * @depends testCreateDocument
    */
    public function testDownloadImageFromDocument($document)
    {
        $download = $this->complycube->documents()->download($document->id, 'front');
        $this->assertEquals('front.jpg', $download->fileName);
    }

    /**
    * @depends testCreateDocument
    */
    public function testDownloadImageFromDocumentInvalidSide($document)
    {
        $download = $this->complycube->documents()->download($document->id, 'INVALIDSIDE');
        $this->assertEquals('front.jpg', $download->fileName);
    }

    public function testDownloadImageFromDocumentNonExistentId()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $download = $this->complycube->documents()->download('nonexistentid', 'INVALIDSIDE');
    }

    /**
    * @depends testCreateDocument
    * @depends testUploadImageToDocument
    */
    public function testDeleteImageFromDocument($document, $img)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->documents()->deleteImage($document->id, $img->documentSide);
        $this->complycube->documents()->download($document->id, $img->documentSide);
    }

    public function testDeleteImageNonExistentImage()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->documents()->deleteImage('nonexistentid', 'front');
    }

    public function testDeleteNonExistentDocument()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->documents()->delete('nonexistentid');
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testListDocuments($clientId)
    {
        $documents = $this->complycube->documents()->list($clientId);
        $this->assertGreaterThan(0, $documents ->totalItems);
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testList2DocumentsOnly($clientId)
    {
        $this->testCreateDocument($clientId);
        $this->testCreateDocument($clientId);
        $documents = $this->complycube->documents()->list($clientId, ['page' => 1, 'pageSize' => 2]);
        $this->assertEquals(2, iterator_count($documents));
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testFilterPassportDocumentsOnly($clientId)
    {
        $this->testCreateDocument($clientId);
        $documents = $this->complycube->documents()->list($clientId, ['type' => 'passport']);
        foreach ($documents as $doc) {
            $this->assertEquals('passport', $doc->type);
        }
    }
}
