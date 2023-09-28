<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Check;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\ReportApi
 */
class ReportTest extends TestCase
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
        $check->type = "extensive_screening_check";
        $result = $this->complycube->checks()->create($clientId, $check);
        $this->assertEquals($result->type, $result->type);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateClientReport(string $clientId): void
    {
        $result = $this->complycube
            ->reports()
            ->generate(["clientId" => $clientId]);
        $this->assertNotNull($result->contentType);
        $this->assertNotNull($result->data);
    }

    /**
     * @depends testCreateSimpleCheck
     */
    public function testGenerateCheckReport($checkId): void
    {
        $check = $this->complycube->checks()->get($checkId);
        while ($check->status == "pending") {
            sleep(5);
            $check = $this->complycube->checks()->get($checkId);
        }

        $result = $this->complycube
            ->reports()
            ->generate(["checkId" => $check->id]);
        $this->assertNotNull($result->contentType);
        $this->assertNotNull($result->data);
    }
}
