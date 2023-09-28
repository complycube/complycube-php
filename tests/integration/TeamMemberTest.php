<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\TeamMemberApi
 */
class TeamMemberTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testListTeamMembers()
    {
        $result = $this->complycube->teamMembers()->list();
        $this->assertGreaterThan(0, $result->totalItems);
        return $result->current();
    }

    /**
     * @depends testListTeamMembers
     */
    public function testGetTeamMember($teamMember)
    {
        $result = $this->complycube->teamMembers()->get($teamMember->id);
        $this->assertEquals($teamMember->id, $result->id);
    }

    public function testGetNonExistentTeamMember()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->teamMembers()->get("NONEXISTENT");
    }

    /**
     * @depends testListTeamMembers
     */
    public function testFilterTeamMember($teamMember)
    {
        $amember = $this->complycube->teamMembers()->get($teamMember->id);
        $result = $this->complycube
            ->teamMembers()
            ->list(["role" => $amember->role]);
        foreach ($result as $res) {
            $this->assertEquals($amember->role, $res->role);
        }
    }
}
