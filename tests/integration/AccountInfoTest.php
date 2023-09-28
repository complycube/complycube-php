<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\AccountInfo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\AccountInfoApi
 */
class AccountInfoTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testGetAccountInfo(): void
    {
        $result = $this->complycube->accountInfo()->get();
        $this->assertInstanceOf(AccountInfo::class, $result);
        $this->assertNotNull($result->username);
        $this->assertNotNull($result->plan);
    }
}
