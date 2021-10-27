<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Report;

/**
 * @covers \ComplyCube\ComplyCubeClient
 */
class StaticTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testScreeningLists(): void
    {
        $result = $this->complycube->screeningLists();
        $this->assertGreaterThan(0, count($result));
    }
}
