<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\Client;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \ComplyCube\Resources\ClientApi
 */
class ClientTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private ?Client $personClient;
    private ?Client $companyClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }

        $this->personClient = new Client([
            "type" => "person",
            "email" => "john@doe.com",
            "personDetails" => [
                "firstName" => "Jane",
                "middleName" => "Middle",
                "lastName" => "Smith",
                "dob" => "1970-01-01",
                "gender" => "female",
                "nationality" => "GB",
                "birthCountry" => "US",
                "ssn" => "111111111",
                "socialInsuranceNumber" => "SI00000000",
                "nationalIdentityNumber" => "NI00000000",
                "taxIdentificationNumber" => "TIN0000000",
            ],
        ]);

        $this->companyClient = new Client([
            "type" => "company",
            "email" => "panama@holdings.com",
            "companyDetails" => [
                "name" => "Panama Holdings",
            ],
        ]);
    }

    public function testCreatePersonInline(): string
    {
        $result = $this->complycube->clients()->create([
            "type" => "person",
            "email" => "john@doe.com",
            "personDetails" => [
                "firstName" => "Jane",
                "lastName" => "Smith",
                "ssn" => "111111111",
            ],
        ]);

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

    public function testClientMissingEmail()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->email = null;
        $this->complycube->clients()->create($this->personClient);
    }

    public function testClientMissingType()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->type = null;
        $this->complycube->clients()->create($this->personClient);
    }

    public function testPersonMissingFirstName()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->personDetails->firstName = null;
        $this->complycube->clients()->create($this->personClient);
    }

    public function testPersonMissingLastName()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->personDetails->lastName = null;
        $this->complycube->clients()->create($this->personClient);
    }

    public function testCreateCompanyClient(): string
    {
        $result = $this->complycube->clients()->create($this->companyClient);
        $this->assertEquals(
            $this->companyClient->companyDetails->name,
            $result->companyDetails->name
        );
        $this->assertEquals($this->companyClient->email, $result->email);
        $this->assertEquals($this->companyClient->type, $result->type);
        return $result->id;
    }

    public function testCreateCompanyClientWithValidWebsite()
    {
        $this->companyClient->companyDetails->website =
            "https://www.google.com";
        $result = $this->complycube->clients()->create($this->companyClient);
        $this->assertEquals(
            $this->companyClient->companyDetails->name,
            $result->companyDetails->name
        );
        $this->assertEquals(
            $this->companyClient->companyDetails->website,
            $result->companyDetails->website
        );
        $this->assertEquals($this->companyClient->email, $result->email);
        $this->assertEquals($this->companyClient->type, $result->type);
    }

    /**
     * @depends testCreateCompanyClient
     */
    public function testUpdateCompanyClientWithValidWebsite($id)
    {
        $result = $this->complycube->clients()->update($id, [
            "companyDetails" => ["website" => "https://www.google.com"],
        ]);

        $this->assertEquals(
            $this->companyClient->companyDetails->name,
            $result->companyDetails->name
        );
        $this->assertEquals(
            "https://www.google.com",
            $result->companyDetails->website
        );
        $this->assertEquals($this->companyClient->email, $result->email);
        $this->assertEquals($result->type, "company");
    }

    public function testCreateClientWithValidMetadata()
    {
        $this->personClient->metadata = (object) [
            "key1" => "value1",
            "key2" => "value2",
            "key3" => "value3",
        ];

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
        $this->assertEquals($this->personClient->metadata, $result->metadata);
    }
    public function testCreateClientWithValidMetadataInline()
    {
        $this->personClient->metadata = [
            "key1" => "value1",
            "key2" => "value2",
            "key3" => "value3",
        ];

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
        $this->assertEquals(
            (object) $this->personClient->metadata,
            $result->metadata
        );
    }

    public function testCreateClientWithMetadataContainingNonStringKeys()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->metadata = ["value1", "value2", "value3"];
        $this->complycube->clients()->create($this->personClient);
    }
    public function testCreateClientWithMetadataContainingNonStringValues()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->metadata = [
            "key1" => 1,
            "key2" => 1.5,
            "key3" => true,
            "key4" => null,
            "key5" => [1, 2, 3],
            "key6" => [
                "a" => 1,
                "b" => 2,
                "c" => 3,
            ],
            "key7" => (function () {
                $obj = new stdClass();
                $obj->a = 1;
                $obj->b = 2;
                $obj->c = 3;
                return $obj;
            })(),
        ];
        $this->complycube->clients()->create($this->personClient);
    }
    public function testCreateClientWithMetadataContainingInvalidLengthKeys()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->metadata = [
            str_repeat("key1", 11) => "value1",
            "key2" => "value2",
            "key3" => "value3",
        ];
        $this->complycube->clients()->create($this->personClient);
    }
    public function testCreateClientWithMetadataContainingInvalidLengthValues()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->personClient->metadata = [
            "key1" => str_repeat("value1", 84),
            "key2" => "value2",
            "key3" => "value3",
        ];
        $this->complycube->clients()->create($this->personClient);
    }
    public function testCreateClientWithMetadataEntriesExceedingCountLimit()
    {
        $this->expectException(ComplyCubeClientException::class);
        for ($i = 0; $i < 21; $i++) {
            $this->personClient->metadata["$i"] = "$i";
        }
        $this->complycube->clients()->create($this->personClient);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithValidMetadata($id)
    {
        $metadata = (object) [
            "key1" => "value1",
            "key2" => "value2",
            "key3" => "value3",
        ];

        $result = $this->complycube->clients()->update($id, [
            "metadata" => $metadata,
        ]);
        $this->assertEquals(
            $this->personClient->personDetails->firstName,
            $result->personDetails->firstName
        );
        $this->assertEquals(
            $this->personClient->personDetails->lastName,
            $result->personDetails->lastName
        );
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($result->type, "person");
        $this->assertEquals($metadata, $result->metadata);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithValidMetadataInline($id)
    {
        $metadata = [
            "key1" => "value1",
            "key2" => "value2",
            "key3" => "value3",
        ];

        $result = $this->complycube->clients()->update($id, $metadata);
        $this->assertEquals(
            $this->personClient->personDetails->firstName,
            $result->personDetails->firstName
        );
        $this->assertEquals(
            $this->personClient->personDetails->lastName,
            $result->personDetails->lastName
        );
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($result->type, "person");
        $this->assertEquals((object) $metadata, $result->metadata);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithMetadataContainingNonStringKeys($id)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->clients()->update($id, [
            "metadata" => ["value1", "value2", "value3"],
        ]);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithMetadataContainingNonStringValues($id)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->clients()->update($id, [
            "metadata" => [
                "key1" => 1,
                "key2" => 1.5,
                "key3" => true,
                "key4" => null,
                "key5" => [1, 2, 3],
                "key6" => [
                    "a" => 1,
                    "b" => 2,
                    "c" => 3,
                ],
                "key7" => (object) ["a" => 1, "b" => 2, "c" => 3],
            ],
        ]);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithMetadataContainingInvalidLengthKeys($id)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->clients()->update($id, [
            "metadata" => [
                str_repeat("key1", 11) => "value1",
                "key2" => "value2",
                "key3" => "value3",
            ],
        ]);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithMetadataContainingInvalidLengthValues(
        $id
    ) {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->clients()->update($id, [
            "metadata" => [
                "key1" => str_repeat("value1", 84),
                "key2" => "value2",
                "key3" => "value3",
            ],
        ]);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdateClientWithMetadataEntriesExceedingCountLimit($id)
    {
        $this->expectException(ComplyCubeClientException::class);

        $metadata = [];

        for ($i = 0; $i < 21; $i++) {
            $metadata["$i"] = "$i";
        }

        $this->complycube->clients()->update($id, [
            "metadata" => $metadata,
        ]);
    }

    public function testGetNonExistentPersonById()
    {
        try {
            $this->complycube->clients()->get("nonexistentclientid");
        } catch (ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGetPersonById($id)
    {
        $retrievedClient = $this->complycube->clients()->get($id);
        $this->assertEquals(
            $this->personClient->personDetails->firstName,
            $retrievedClient->personDetails->firstName
        );
        $this->assertEquals(
            $this->personClient->personDetails->lastName,
            $retrievedClient->personDetails->lastName
        );
        $this->assertEquals(
            $this->personClient->email,
            $retrievedClient->email
        );
        $this->assertEquals($this->personClient->type, $retrievedClient->type);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdatePerson($id)
    {
        $updatedClient = $this->complycube->clients()->update($id, [
            "email" => "updated@email.com",
        ]);
        $retrievedClient = $this->complycube->clients()->get($id);
        $this->assertEquals($updatedClient->email, $retrievedClient->email);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testUpdatePersonWithUselessInformation($id)
    {
        $updatedClient = $this->complycube->clients()->update($id, [
            "email" => "adifferent@email.com",
            "companyDetails" => [
                "website" => "http://awebsite.com",
            ],
        ]);
        $this->assertEquals("adifferent@email.com", $updatedClient->email);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testDeletePerson($id)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->clients()->delete($id);
        $this->complycube->clients()->get($id);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testListPersons()
    {
        $clients = $this->complycube->clients()->list();
        $this->assertGreaterThan(0, $clients->totalItems);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testList2PersonsOnly()
    {
        $clients = $this->complycube
            ->clients()
            ->list(["page" => 1, "pageSize" => 2]);
        $this->assertEquals(2, iterator_count($clients));
    }

    public function testFilterCompaniesOnly()
    {
        $clients = $this->complycube->clients()->list(["type" => "company"]);
        foreach ($clients as $client) {
            $this->assertEquals("company", $client->type);
        }
    }
}
