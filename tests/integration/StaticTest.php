<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\ComplyCubeClient
 */
class StaticTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testScreeningLists(): void
    {
        $result = $this->complycube->screeningLists();
        $this->assertIsIterable($result);
        $this->assertGreaterThan(0, count($result));
    }

    public function testSupportedDocuments(): void
    {
        $result = $this->complycube->supportedDocuments();
        $this->assertIsIterable($result);
        $this->assertGreaterThan(0, count($result));
    }
}
