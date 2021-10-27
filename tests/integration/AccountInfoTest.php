<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\AccountInfo;

/**
 * @covers \ComplyCube\Resources\AccountInfoApi
 */
class AccountInfoTest extends \PHPUnit\Framework\TestCase
{
    private $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testGetAccountInfo(): void
    {
        $result = $this->complycube->accountInfo()->get();
        $this->assertNotNull($result->username);
        $this->assertNotNull($result->plan);
    }
}
