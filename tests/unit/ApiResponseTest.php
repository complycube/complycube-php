<?php

namespace ComplyCube\Tests\Unit;

use ComplyCube\ApiResponse;

/**
 * @covers \ComplyCube\ApiResponse
 */
class ApiResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testNoApiResponse()
    {
        $response = new ApiResponse(200, null);
        $this->assertEquals((object)[], $response->getDecodedBody());
    }

    public function testSuccessStatusCodeResponse()
    {
        $response = new ApiResponse(200, null);
        $this->assertEquals(200, $response->getHttpStatusCode());
    }

    public function testHeadersResponse()
    {
        $headers = ['A-Header' => 'A-Value'];
        $response = new ApiResponse(200, null, $headers);
        $this->assertEquals($headers, $response->getHeaders());
    }

    public function testBodyResponse()
    {
        $body = '{}';
        $response = new ApiResponse(200, $body);
        $this->assertEquals($body, $response->getBody());
    }

    public function testEmptyApiResponse()
    {
        $response = new ApiResponse(200, "{}");
        $this->assertEquals((object)[], $response->getDecodedBody());
    }

    public function testSingleLevelApiResponse()
    {
        $response = new ApiResponse(200, "{ \"key\" : \"value\" }");
        $body = $response->getDecodedBody();
        $this->assertObjectHasAttribute('key', $body);
        $this->assertEquals('value', $body->key);
    }

    public function testNestedApiResponse()
    {
        $response = new ApiResponse(200, "{ \"key\": { \"nestedKey\" : \"nestedValue\" } }");
        $body = $response->getDecodedBody();
        $this->assertObjectHasAttribute('key', $body);
        $this->assertObjectHasAttribute('nestedKey', $body->key);
        $this->assertEquals('nestedValue', $body->key->nestedKey);
    }

    public function testInvalidJsonResponse()
    {
        $this->expectException(\JsonException::class);
        $response = new ApiResponse(200, "InvalidJSON");
    }
}
