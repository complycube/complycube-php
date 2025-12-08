<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\WorkflowSession;
use ComplyCube\Resources\WorkflowSessionApi;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\Resources\WorkflowSessionApi
 */
class WorkflowSessionTest extends TestCase
{
    private ?ComplyCubeClient $complycube;
    private WorkflowSessionApi $workflowSessions;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
        $this->workflowSessions = new WorkflowSessionApi(
            $this->complycube->apiClient
        );
    }

    public function testListWorkflowSessions(): string
    {
        $result = $this->workflowSessions->list();

        if (empty($result->items) || $result->totalItems === 0) {
            $this->markTestSkipped(
                "No workflow sessions exist in this account. Create a workflow session and rerun."
            );
        }

        $firstSession = $result->current();
        $this->assertInstanceOf(WorkflowSession::class, $firstSession);
        $this->assertNotEmpty($firstSession->id);

        return $firstSession->id;
    }

    /**
     * @depends testListWorkflowSessions
     */
    public function testGetWorkflowSession(string $workflowSessionId): void
    {
        $session = $this->workflowSessions->get($workflowSessionId);

        $this->assertEquals($workflowSessionId, $session->id);
        $this->assertNotNull($session->workflowId);
        $this->assertNotNull($session->status);
    }

    public function testCompleteStartedWorkflowSession(): void
    {
        $sessions = $this->workflowSessions->list();

        if (empty($sessions->items) || $sessions->totalItems === 0) {
            $this->markTestSkipped(
                "No workflow sessions exist in this account. Create a workflow session and rerun."
            );
        }

        $startedSession = null;
        foreach ($sessions as $sessionSummary) {
            $session = $this->workflowSessions->get($sessionSummary->id);
            if ($session->status === "started") {
                $startedSession = $session;
                break;
            }
        }

        if ($startedSession === null) {
            $this->markTestSkipped(
                "No workflow sessions with status 'started' available to complete."
            );
        }

        $response = $this->workflowSessions->complete($startedSession->id);

        $this->assertContains(
            $response->getHttpStatusCode(),
            [200, 204],
            "Workflow session completion did not return a success code."
        );

        $completedSession = $this->workflowSessions->get($startedSession->id);
        $this->assertNotEquals(
            "started",
            $completedSession->status,
            "Workflow session status should change after completion."
        );
    }
}
