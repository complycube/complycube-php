<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\RiskProfileApi
 */
class RiskProfileTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private ?Client $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        if (empty($this->personClient)) {
            $personDetails = new PersonDetails();
            $personDetails->firstName = "Richard";
            $personDetails->lastName = "Nixon";
            $newClient = new Client();
            $newClient->type = "person";
            $newClient->email = "john@doe.com";
            $newClient->personDetails = $personDetails;
            $this->personClient = $newClient;
        }
    }

    public function testCreatePersonForDocument(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    /**
     * @depends testCreatePersonForDocument
     */
    public function testGetRiskProfile($clientId)
    {
        $result = $this->complycube->riskProfiles()->get($clientId);
        $this->assertNotNull($result->overall);
        $this->assertNotNull($result->countryRisk);
        $this->assertNotNull($result->politicalExposureRisk);
        $this->assertNotNull($result->occupationRisk);
        $this->assertNotNull($result->watchlistRisk);
    }
}
