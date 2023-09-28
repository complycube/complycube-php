<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\Address;
use ComplyCube\Model\Client;
use ComplyCube\Model\ComplyCubeCollection;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \ComplyCube\Resources\AddressApi
 */
class AddressTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private ?Address $address;
    private ?Client $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }

        $this->address = new Address([
            "line" => "11 Something Avenue",
            "city" => "London",
            "country" => "GB",
            "postalCode" => "E14 4PP"
        ]);

        $this->personClient = new Client([
            "type" => "person",
            "email" => "john@doe.com",
            "personDetails" => [
                "firstName" => "John",
                "lastName" => "Smith",
            ],
        ]);
    }

    public function testCreatePersonForAddress(): string
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

    public function testGetNonExistentAddress()
    {
        try {
            $this->complycube->address()->get("nonexistentaddressid");
        } catch (ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddress($clientId): Address
    {
        $result = $this->complycube
            ->address()
            ->create($clientId, $this->address);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals($this->address->country, $result->country);
        return $result;
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddressInvalidType($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->address->type = "INVALIDTYPE";
        $this->complycube->address()->create($clientId, $this->address);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateInvalidCountryAddress($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->address->country = "J{";
        $this->complycube->address()->create($clientId, $this->address);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddressMissingLine($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->address->line = null;
        $this->complycube->address()->create($clientId, $this->address);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddressInLine($clientId)
    {
        $result = $this->complycube->address()->create($clientId, [
            "line" => "11 Something Avenue",
            "city" => "London",
            "country" => "GB",
        ]);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals($this->address->country, $result->country);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddressMissingCity($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->address->city = null;
        $this->complycube->address()->create($clientId, $this->address);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testCreateAddressMissingCountry($clientId)
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->address->country = null;
        $this->complycube->address()->create($clientId, $this->address);
    }

    /**
     * @depends testCreateAddress
     */
    public function testGetAddress($address): void
    {
        $result = $this->complycube->address()->get($address->id);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals($this->address->country, $result->country);
    }

    /**
     * @depends testCreateAddress
     */
    public function testUpdateAddress($address): void
    {
        $result = $this->complycube->address()->update($address->id, [
            "country" => "US",
        ]);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals("US", $result->country);
    }

    /**
     * @depends testCreateAddress
     */
    public function testUpdateAddressInvalidCountryCode($address): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->address()->update($address->id, [
            "country" => "NOTACOUNTRYCODE",
        ]);
    }

    /**
     * @depends testCreateAddress
     */
    public function testListAddress($address): void
    {
        $addresslist = $this->complycube->address()->list($address->clientId);
        $this->assertGreaterThan(0, $addresslist->totalItems);
    }

    /**
     * @depends testCreatePersonForAddress
     */
    public function testList2AddressesOnly($clientId): void
    {
        $this->complycube->address()->create($clientId, $this->address);
        $this->address->city = "New York";
        $this->complycube->address()->create($clientId, $this->address);
        $this->address->city = "Chicago";
        $this->complycube->address()->create($clientId, $this->address);
        $addresslist = $this->complycube
            ->address()
            ->list($clientId, ["page" => 1, "pageSize" => 2]);
        $this->assertEquals(2, iterator_count($addresslist));
    }

    /**
     * @depends testCreateAddress
     */
    public function testFilterAddressesLondonOnly($address): void
    {
        $addresslist = $this->complycube
            ->address()
            ->list($address->clientId, ["city" => "London"]);
        foreach ($addresslist as $anAddress) {
            $this->assertEquals("London", $anAddress->city);
        }
    }

    /**
     * @depends testCreateAddress
     */
    public function atestSearchAddress(): void
    {
        $result = $this->complycube->address()->search([
            "line" => "11 Something Avenue",
            "country" => "GB",
            "postalCode" => "E14 4PP",
        ]);

        $this->assertInstanceOf(ComplyCubeCollection::class, $result);

        $this->assertCount(1, $result);

        foreach ($result->items as $item) {
            $this->assertInstanceOf(Address::class, $item);

            foreach (
                ["line", "city", "state", "postalCode", "country"]
                as $key
            ) {
                $this->assertNotNull($item->$key);
            }

            foreach (["createdAt", "updatedAt"] as $property_name) {
                $item_reflection_property = new ReflectionProperty(
                    $item,
                    $property_name
                );

                $item_reflection_property->setAccessible(true);

                $this->assertNull($item_reflection_property->getValue($item));
            }

            foreach (
                [
                    "id",
                    "clientId",
                    "type",
                    "propertyNumber",
                    "buildingName",
                    "fromDate",
                    "toDate",
                ]
                as $key
            ) {
                $this->assertNull($item->$key);
            }
        }
    }

    public function testSearchAddressWithInvalidParameters(): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->address()->search([
            "param_1" => "qzdqzd",
            "param_2" => "90001",
            "param_3" => "GB",
        ]);
    }

    /**
     * @depends testCreateAddress
     */
    public function testDeleteAddress($address): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->address()->delete($address->id);
        $this->complycube->address()->get($address->id);
    }
}
