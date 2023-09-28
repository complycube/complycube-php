<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Client;
use ComplyCube\Model\FlowSession;
use ComplyCube\Model\PersonDetails;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\FlowSessionApi
 */
class FlowSessionTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private ?Client $personClient;
    private ?FlowSession $fs;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $personDetails = new PersonDetails();
        $personDetails->firstName = "Richard";
        $personDetails->lastName = "Nixon";
        $newClient = new Client();
        $newClient->type = "person";
        $newClient->email = "john@doe.com";
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;

        $this->fs = new FlowSession();
        $this->fs->checkTypes = ["extensive_screening_check"];
        $this->fs->successUrl = "https://complycube.com";
        $this->fs->cancelUrl = "https://complycube.com";
    }

    public function testCreatePersonForSession(): string
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
     * @depends testCreatePersonForSession
     */
    public function testCreateFlowSession($clientId)
    {
        $flowSession = $this->fs;
        $flowSession->clientId = $clientId;
        $result = $this->complycube->flow()->createSession($flowSession);
        $this->assertNotNull($result->redirectUrl);
    }

    /**
     * @depends testCreatePersonForSession
     */
    public function testCreateFlowSessionInline($clientId)
    {
        $result = $this->complycube->flow()->createSession([
            "clientId" => $clientId,
            "checkTypes" => $this->fs->checkTypes,
            "successUrl" => $this->fs->successUrl,
            "cancelUrl" => $this->fs->cancelUrl,
        ]);
        $this->assertNotNull($result->redirectUrl);
    }
}
