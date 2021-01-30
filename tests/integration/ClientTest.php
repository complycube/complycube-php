<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\CompanyDetails;
use ComplyCube\Model\Client;

/**
 * @covers \ComplyCube\Resources\ClientApi
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;
    private $personClient;
    private $companyClient;
    
    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $personDetails = new PersonDetails();
        $personDetails->firstName = 'John';
        $personDetails->lastName = 'Smith';
        $newClient = new Client();
        $newClient->type = 'person';
        $newClient->email = 'john@doe.com';
        $newClient->personDetails = $personDetails;
        $this->personClient = $newClient;

        $companyDetails = new CompanyDetails();
        $companyDetails->name = 'Panama Holdings';
        $company = new Client();
        $company->type = 'company';
        $company->email = 'panama@holdings.com';
        $company->companyDetails = $companyDetails;
        $this->companyClient = $company;
    }

    public function testCreatePersonInline(): string
    {
        $result = $this->complycube->clients()->create(['type' => 'person',
                                                        'email' => 'john@doe.com',
                                                        'personDetails' => ['firstName' => 'John',
                                                                            'lastName' => 'Smith']]);
                                                                            
        $this->assertEquals($this->personClient->personDetails->firstName, $result->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $result->personDetails->lastName);
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    public function testCreatePersonClient(): string
    {
        $result = $this->complycube->clients()->create($this->personClient);
        $this->assertEquals($this->personClient->personDetails->firstName, $result->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $result->personDetails->lastName);
        $this->assertEquals($this->personClient->email, $result->email);
        $this->assertEquals($this->personClient->type, $result->type);
        return $result->id;
    }

    public function testClientMissingEmail()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $newClient = $this->personClient;
        $newClient->email = null;
        $result = $this->complycube->clients()->create($newClient);
    }

    public function testClientMissingType()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $newClient = $this->personClient;
        $newClient->type = null;
        $result = $this->complycube->clients()->create($newClient);
    }

    public function testPersonMissingFirstName()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $newClient = $this->personClient;
        $newClient->personDetails->firstName = null;
        $result = $this->complycube->clients()->create($newClient);
    }

    public function testPersonMissingLastName()
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $newClient = $this->personClient;
        $newClient->personDetails->lastName = null;
        $result = $this->complycube->clients()->create($newClient);
    }

    public function testCreateCompanyClient()
    {
        $result = $this->complycube->clients()->create($this->companyClient);
        $this->assertEquals($this->companyClient->companyDetails->name, $result->companyDetails->name);
        $this->assertEquals($this->companyClient->email, $result->email);
        $this->assertEquals($this->companyClient->type, $result->type);
    }
    
    public function testGetNonExistentPersonById()
    {
        try {
            $retrievedClient = $this->complycube->clients()->get('nonexistentclientid');
        } catch (\ComplyCube\Exception\ComplyCubeClientException $e) {
            $this->assertEquals($e->getCode(), 404);
        }
    }

    /**
    * @depends testCreatePersonClient
    */
    public function testGetPersonById($id)
    {
        $retrievedClient = $this->complycube->clients()->get($id);
        $this->assertEquals($this->personClient->personDetails->firstName, $retrievedClient->personDetails->firstName);
        $this->assertEquals($this->personClient->personDetails->lastName, $retrievedClient->personDetails->lastName);
        $this->assertEquals($this->personClient->email, $retrievedClient->email);
        $this->assertEquals($this->personClient->type, $retrievedClient->type);
    }

    /**
    * @depends testCreatePersonClient
    */
    public function testUpdatePerson($id)
    {
        $clientUpdate = new Client();
        $clientUpdate->email = 'updated@email.com';
        $updatedClient = $this->complycube->clients()->update($id, $clientUpdate);
        $retrievedClient = $this->complycube->clients()->get($id);
        $this->assertEquals($clientUpdate->email, $retrievedClient->email);
    }

    /**
    * @depends testCreatePersonClient
    */
    public function testUpdatePersonWithUselessInformation($id)
    {
        $clientUpdate = new Client();
        $clientUpdate->email = 'adifferent@email.com';
        $companyDetails = new CompanyDetails();
        $companyDetails->website = 'http://awebsite.com';
        $clientUpdate->companyDetails = $companyDetails;
        $updatedClient = $this->complycube->clients()->update($id, $clientUpdate);
        $this->assertEquals($clientUpdate->email, $updatedClient->email);
    }

    /**
    * @depends testCreatePersonClient
    */
    public function testDeletePerson($id)
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
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
        $clients = $this->complycube->clients()->list(['page' => 1, 'pageSize' => 2]);
        $this->assertEquals(2, iterator_count($clients));
    }

    public function testFilterCompaniesOnly()
    {
        $clients = $this->complycube->clients()->list(['type' => 'company']);
        foreach ($clients as $client) {
            $this->assertEquals('company', $client->type);
        }
    }
}
