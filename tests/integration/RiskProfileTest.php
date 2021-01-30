<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\RiskProfile;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\Client;

/**
 * @covers \ComplyCube\Resources\RiskProfileApi
 */
class RiskProfileTest extends \PHPUnit\Framework\TestCase
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
