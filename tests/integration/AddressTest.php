<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Address;
use ComplyCube\Model\Client;
use ComplyCube\Model\PersonDetails;

/**
 * @covers \ComplyCube\Resources\AddressApi
 */
class AddressTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;
    private $address;
    private $personClient;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $address = new Address();
        $address->line = '11 Something Avenue';
        $address->city = 'London';
        $address->country = 'GB';
        $this->address = $address;

        $personDetails = new PersonDetails();
        $personDetails->firstName = 'John';
        $personDetails->lastName = 'Smith';
        $newClient = new Client();
        $newClient->type = 'person';
        $newClient->email = 'john@doe.com';
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;
    }

    public function testCreatePersonForAddress(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->personDetails->firstName, $result->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $result->personDetails->lastName);
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    public function testGetNonExistentAddress()
    {
        try {
            $this->complycube->address()->get('nonexistentaddressid');
        } catch (\ComplyCube\Exception\ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }
    
    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateAddress($clientId): Address
    {
        $result = $this->complycube->address()->create($clientId, $this->address);
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
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $invalidType = $this->address;
        $invalidType->type = 'INVALIDTYPE';
        $result = $this->complycube->address()->create($clientId, $this->address);
    }

    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateInvalidCountryAddress($clientId): string
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $invalidCountryAddress = $this->address;
        $invalidCountryAddress->country = 'J{';
        $result = $this->complycube->address()->create($clientId, $this->address);
    }

    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateAddressMissingLine($clientId): string
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $invalidAddress = $this->address;
        $invalidAddress->line = null;
        $result = $this->complycube->address()->create($clientId, $this->address);
    }

    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateAddressInLine($clientId)
    {
        $result = $this->complycube->address()->create($clientId, [ 'line' => '11 Something Avenue',
                                                                        'city' => 'London',
                                                                        'country' => 'GB']);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals($this->address->country, $result->country);
    }

    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateAddressMissingCity($clientId): string
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $invalidAddress = $this->address;
        $invalidAddress->city = null;
        $result = $this->complycube->address()->create($clientId, $this->address);
    }

    /**
    * @depends testCreatePersonForAddress
    */
    public function testCreateAddressMissingCountry($clientId): string
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $invalidAddress = $this->address;
        $invalidAddress->country = null;
        $result = $this->complycube->address()->create($clientId, $this->address);
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
        $updatedAddress = new Address();
        $updatedAddress->country = 'US';
        $result = $this->complycube->address()->update($address->id, $updatedAddress);
        $this->assertEquals($this->address->line, $result->line);
        $this->assertEquals($this->address->city, $result->city);
        $this->assertEquals('US', $result->country);
    }

    /**
    * @depends testCreateAddress
    */
    public function testUpdateAddressInvalidCountryCode($address): void
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $updatedAddress = new Address();
        $updatedAddress->country = 'NOTACOUNTRYCODE';
        $result = $this->complycube->address()->update($address->id, $updatedAddress);
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
        $newAdd = $this->address;
        $result = $this->complycube->address()->create($clientId, $newAdd);
        $newAdd->city = 'New York';
        $result = $this->complycube->address()->create($clientId, $newAdd);
        $newAdd->city = 'Chicago';
        $result = $this->complycube->address()->create($clientId, $newAdd);
        $addresslist = $this->complycube->address()->list($newAdd->clientId, ['page' => 1, 'pageSize' => 2]);
        $this->assertEquals(2, iterator_count($addresslist));
    }

    /**
    * @depends testCreateAddress
    */
    public function testFilterAddressesLondonOnly($address): void
    {
        $addresslist = $this->complycube->address()->list($address->clientId, ['city' => 'London']);
        foreach ($addresslist as $anAddress) {
            $this->assertEquals('London', $anAddress->city);
        }
    }

    /**
    * @depends testCreateAddress
    */
    public function testDeleteAddress($address): void
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->address()->delete($address->id);
        $this->complycube->address()->get($address->id);
    }
}
