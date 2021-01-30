<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\Image;
use ComplyCube\Model\Client;

/**
 * @covers \ComplyCube\Resources\LivePhotoApi
 */
class LivePhotoTest extends \PHPUnit\Framework\TestCase
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
    public function testUploadLivePhoto($clientId): string
    {
        $image = new Image();
        $image->data = file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r");
        $result = $this->complycube->livephotos()->upload($clientId, $image);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }
    
    /**
    * @depends testCreatePersonForDocument
    */
    public function testUploadLivePhotoInline($clientId): string
    {
        $image = file_get_contents("./tests/fixtures/encoded-20200609153459.txt", "r");
        $result = $this->complycube->livephotos()->upload($clientId, ['data' => $image]);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
    * @depends testUploadLivePhoto
    */
    public function testDownloadLivePhoto($livePhotoId)
    {
        $img = $this->complycube->livephotos()->download($livePhotoId);
        $this->assertEquals('images/jpg', $img->contentType);
    }

    /**
    * @depends testUploadLivePhoto
    */
    public function atestDeleteLivePhoto($livePhotoId)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->livephotos()->delete($livePhotoId);
        $this->complycube->livephotos()->get($livePhotoId);
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testListLivePhotos($clientId)
    {
        $this->testUploadLivePhoto($clientId);
        $photos = $this->complycube->livephotos()->list($clientId);
        $this->assertGreaterThan(0, $photos->totalItems);
    }

    /**
    * @depends testCreatePersonForDocument
    */
    public function testList2LivePhotosOnly($clientId)
    {
        $this->testUploadLivePhoto($clientId);
        $this->testUploadLivePhoto($clientId);
        $photos = $this->complycube->livephotos()->list($clientId, ['page' => 1, 'pageSize' => 2]);
        $this->assertEquals(2, iterator_count($photos));
    }
}
