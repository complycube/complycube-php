<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Report;

use ComplyCube\Model\Check;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;

/**
 * @covers \ComplyCube\Resources\ReportApi
 */
class ReportTest extends \PHPUnit\Framework\TestCase
{
    private $personClient;
    private $complycube;

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
    public function testCreateSimpleCheck($clientId): string
    {
        $check = new Check();
        $check->type = 'extensive_screening_check';
        $result = $this->complycube->checks()->create($clientId, $check);
        $this->assertEquals($result->type, $result->type);
        return $result->id;
    }

    /**
    * @depends testCreatePersonClient
    */
    public function testGenerateClientReport(string $clientId): void
    {
        $result = $this->complycube->reports()->generate(['clientId' => $clientId]);
        $this->assertNotNull($result->contentType);
        $this->assertNotNull($result->data);
    }

    /**
    * @depends testCreateSimpleCheck
    */
    public function testGenerateCheckReport($checkId): void
    {
        $result = $this->complycube->reports()->generate(['checkId' => $checkId]);
        $this->assertNotNull($result->contentType);
        $this->assertNotNull($result->data);
    }
}
