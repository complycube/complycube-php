<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\Client;
use ComplyCube\Model\Token;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \ComplyCube\Resources\TokenApi
 */
class SDKTokenTest extends TestCase
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
            $this->personClient = new Client([
                "type" => "person",
                "email" => "john@doe.com",
                "personDetails" => [
                    "firstName" => "Richard",
                    "lastName" => "Nixon",
                ],
            ]);
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
    public function testGenerateWebSDKToken(string $clientId): void
    {
        $result = $this->complycube
            ->tokens()
            ->generate($clientId, "https://referrer.com/*");

        $this->assertInstanceOf(Token::class, $result);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateMobileSDKToken(string $clientId): void
    {
        $result = $this->complycube
            ->tokens()
            ->generate($clientId, "com.myapp.demo.app");

        $this->assertInstanceOf(Token::class, $result);
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateWebSDKTokenWithInvalidClientId(
        string $clientId
    ): void {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube
            ->tokens()
            ->generate("non existent client id", "https://referrer.com/*");
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateMobileSDKTokenWithInvalidClientId(
        string $clientId
    ): void {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube
            ->tokens()
            ->generate("non existent client id", "com.myapp.demo.app");
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateTokenWithInvalidReferrerOrAppId(
        string $clientId
    ): void {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->tokens()->generate($clientId, "random stuff");
    }

    /**
     * @depends testCreatePersonClient
     */
    public function testGenerateTokenWithNullSDKInfo(string $clientId): void
    {
        $this->expectException(TypeError::class);
        $this->complycube->tokens()->generate($clientId, null);
    }
}
