<?php

namespace ComplyCube\Tests\Integration;

use Carbon\Carbon;
use ComplyCube\ComplyCubeClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\AuditLogApi
 */
class AuditLogTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testListAuditLogs(): string
    {
        $result = $this->complycube->AuditLogs()->list();
        $this->assertGreaterThan(0, $result->totalItems);
        return $result->current()->id;
    }

    public function testListOnly10AuditLogs()
    {
        $result = $this->complycube->AuditLogs()->list(["pageSize" => 10]);
        $this->assertEquals(10, iterator_count($result));
    }

    public function testFilterUpdateOnly()
    {
        $result = $this->complycube->AuditLogs()->list(["action" => "update"]);
        foreach ($result as $res) {
            $this->assertEquals("update", $res->action);
        }
    }

    /**
     * @depends testListAuditLogs
     */
    public function testGetAuditLog($id)
    {
        $result = $this->complycube->AuditLogs()->get($id);
        $this->assertEquals($id, $result->id);
        $this->assertLessThan(Carbon::now(), $result->createdAt);
    }
}
