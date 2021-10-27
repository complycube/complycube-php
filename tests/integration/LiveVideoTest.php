<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\LiveVideo;
use ComplyCube\Model\Client;

/**
 * @covers \ComplyCube\Resources\LiveVideoApi
 */
class LiveVideoTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $personDetails = new PersonDetails();
        $personDetails->firstName = 'John';
        $personDetails->lastName = 'Doe';
        $newClient = new Client();
        $newClient->type = 'person';
        $newClient->email = 'john@doe.com';
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;
    }

    public function testCreatePersonForVideo(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->personDetails->firstName, $result->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $result->personDetails->lastName);
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    /**
    * @depends testCreatePersonForVideo
    */
    public function testCreateLiveVideo($clientId): string
    {
        $response = $this->complycube->apiClient->post('liveVideos',[], ['clientId' => $clientId]);
        $lv = new LiveVideo();
        $lv->load($response->getDecodedBody());
        $this->assertEquals($clientId, $lv->clientId);
        $this->assertNotNull($lv->language);
        $this->assertNotNull($lv->challenges);
        $this->assertGreaterThan(0, count($lv->challenges));
        return $lv->id;
    }

    /**
    * @depends testCreatePersonForVideo
    * @depends testCreateLiveVideo
    */
    public function testGetLiveVideo($clientId, $liveVideoId)
    {
        $lv = new LiveVideo();
        $result = $this->complycube->livevideos()->get($liveVideoId);
        $this->assertEquals($liveVideoId, $result->id);
        $this->assertEquals($clientId, $result->clientId);
        $this->assertNotNull($result->language);
        $this->assertNotNull($result->challenges);
        $this->assertGreaterThan(0, count($result->challenges));
    }
    
    /**
    * @depends testCreatePersonForVideo
    */
    public function testListLiveVideo($clientId)
    {
        $this->testCreateLiveVideo($clientId);
        $videos = $this->complycube->livevideos()->list($clientId);
        $this->assertGreaterThan(0, $videos->totalItems);
    }

    /**
    * @depends testCreateLiveVideo
    */
    public function testDeleteLivePhoto($liveVideoId)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->livevideos()->delete($liveVideoId);
        $this->complycube->livevideos()->get($liveVideoId);
    }
}
