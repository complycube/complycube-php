<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\Check;
use ComplyCube\Model\CheckOptions;
use ComplyCube\Model\Client;
use ComplyCube\Model\Image;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\Validation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\CheckApi
 */
class CheckTest extends TestCase
{
    private ?Check $check;
    private ?ComplyCubeClient $complycube;
    private ?Client $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }

        $personDetails = new PersonDetails();
        $personDetails->firstName = "Boris";
        $personDetails->lastName = "Johnson";

        $newClient = new Client();
        $newClient->type = "person";
        $newClient->email = "john@doe.com";
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;

        $check = new Check();
        $check->type = "extensive_screening_check";
        $this->check = $check;
    }

    public function testCreatePersonClient(): string
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
     * @depends testCreatePersonClient
     */
    public function testCreateDocument($clientId): string
    {
        $result = $this->complycube->documents()->create($clientId, [
            "type" => "driving_license",
            "issuingCountry" => "GB",
        ]);
        $this->assertEquals("driving_license", $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testCreateAddress($clientId): string
    {
        $testAddress = [
            "line" => "11 Something Avenue",
            "city" => "London",
            "country" => "GB",
        ];
        $result = $this->complycube->address()->create($clientId, $testAddress);
        $this->assertEquals($testAddress["line"], $result->line);
        $this->assertEquals($testAddress["city"], $result->city);
        $this->assertEquals($testAddress["country"], $result->country);
        return $result->id;
    }

    /**
     * @depends testCreateDocument
     */
    public function testUploadImageToDocument($documentId): Image
    {
        $image = new Image();
        $image->fileName = "front.jpg";
        $image->data = file_get_contents(
            "./tests/fixtures/encoded-20200609153459.txt"
        );
        $result = $this->complycube
            ->documents()
            ->upload($documentId, "front", $image);
        $this->assertEquals($image->fileName, $result->fileName);
        $this->assertEquals("front", $result->documentSide);
        $this->assertEquals("image/jpg", $result->contentType);
        return $result;
    }

    /**
     * @depends testCreatePersonClient
     * @depends testCreateDocument
     */
    public function testCreateDocumentCheck($clientId, $documentId): string
    {
        $result = $this->complycube->checks()->create($clientId, [
            "type" => "document_check",
            "documentId" => $documentId,
        ]);
        $this->assertTrue(property_exists($result, "id"));
        $this->assertEquals("document_check", $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testCreateExtensiveCheck($clientId): string
    {
        $result = $this->complycube->checks()->create($clientId, $this->check);
        $this->assertTrue(property_exists($result, "id"));
        $this->assertEquals($this->check->type, $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testCreateStandardCheck($clientId): string
    {
        $simpleCheck = $this->check;
        $simpleCheck->type = "standard_screening_check";
        $result = $this->complycube->checks()->create($clientId, $simpleCheck);
        $this->assertTrue(property_exists($result, "id"));
        $this->assertEquals($this->check->type, $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testCreateStandardCheckWithOptions($clientId): string
    {
        $simpleCheck = $this->check;
        $simpleCheck->type = "standard_screening_check";
        $checkOptions = new CheckOptions();
        $checkOptions->screeningClassification = [
            "pepLevel1",
            "watchlistSanctionsLists",
        ];
        $result = $this->complycube->checks()->create($clientId, $simpleCheck);
        $this->assertTrue(property_exists($result, "id"));
        $this->assertEquals($this->check->type, $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testCreateInvalidCheck($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $invalidCheck = $this->check;
        $invalidCheck->type = "invaid_check_type";
        $this->complycube->checks()->create($clientId, $invalidCheck);
    }

    /**
     * @depends testCreateStandardCheck
     */
    public function testGetCheckById($id): Check
    {
        $result = $this->complycube->checks()->get($id);
        $this->assertEquals($id, $result->id);
        return $result;
    }

    /**
     * @depends testCreatePersonClient
     * @depends testCreateDocument
     * @depends testCreateAddress
     */
    public function testCreatePoACheck(
        $clientId,
        $documentId,
        $addressId
    ): string {
        $result = $this->complycube->checks()->create($clientId, [
            "type" => "proof_of_address_check",
            "documentId" => $documentId,
            "addressId" => $addressId,
        ]);
        $this->assertTrue(property_exists($result, "id"));
        $this->assertEquals("proof_of_address_check", $result->type);
        $this->assertEquals($clientId, $result->clientId);
        return $result->id;
    }

    /**
     * @depends testGetCheckById
     */
    public function testValidateCheckByIdInline($check)
    {
        $check = $this->complycube->checks()->get($check->id);
        while ($check->status == "pending") {
            sleep(5);
            $check = $this->complycube->checks()->get($check->id);
        }
        foreach ($check->result->breakdown->matches as $match) {
            $this->complycube->checks()->validate($check->id, [
                "matchId" => $match->id,
                "outcome" => "rejected",
            ]);
        }
        $check = $this->complycube->checks()->get($check->id);
        $this->assertEquals("clear", $check->result->outcome);
    }

    /**
     * @depends testGetCheckById
     */
    public function testValidateCheckById($check)
    {
        $check = $this->complycube->checks()->get($check->id);
        while ($check->status == "pending") {
            sleep(5);
            $check = $this->complycube->checks()->get($check->id);
        }
        foreach ($check->result->breakdown->matches as $match) {
            $validation = new Validation("rejected");
            $validation->matchId = $match->id;
            $this->complycube->checks()->validate($check->id, $validation);
        }
        $check = $this->complycube->checks()->get($check->id);
        $this->assertEquals("clear", $check->result->outcome);
    }

    /**
     * @depends testCreateStandardCheck
     */
    public function testUpdateCheck($id)
    {
        $updatedCheck = new Check();
        $updatedCheck->enableMonitoring = true;
        $result = $this->complycube->checks()->update($id, $updatedCheck);
        $this->assertEquals(
            $updatedCheck->enableMonitoring,
            $result->enableMonitoring
        );
    }

    public function testListChecks()
    {
        $checks = $this->complycube->checks()->list();
        $this->assertGreaterThan(0, $checks->totalItems);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testList2ChecksOnly($clientId)
    {
        $this->testCreateStandardCheck($clientId);
        $this->testCreateStandardCheck($clientId);
        $checks = $this->complycube
            ->checks()
            ->list(["page" => 1, "pageSize" => 2]);
        $this->assertEquals(2, iterator_count($checks));
    }

    public function testFilterExtensiveChecksOnly()
    {
        $checks = $this->complycube
            ->checks()
            ->list(["type" => "extensive_screening_check"]);
        foreach ($checks as $chk) {
            $this->assertEquals("extensive_screening_check", $chk->type);
        }
    }
}
