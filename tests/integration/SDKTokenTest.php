<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Report;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;

/**
 * @covers \ComplyCube\Resources\TokenApi
 */
class SDKTokenTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;
    private $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }

        if (empty($this->personClient)) {
            $personDetails = new PersonDetails();
            $personDetails->firstName = 'Richard';
            $personDetails->lastName = 'Nixon';
            $newClient = new Client();
            $newClient->type = 'person';
            $newClient->email = 'john@doe.com';
            $newClient->personDetails = $personDetails;
            $this->personClient = $newClient;
        }
    }

    public function testCreatePersonClient(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateToken(string $clientId): void
    {
        $result = $this->complycube->tokens()->generate($clientId, 'https://referrer.com/*');
        $this->assertNotNull($result->token);
    }
}
