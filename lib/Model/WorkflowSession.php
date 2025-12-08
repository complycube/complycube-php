<?php

namespace ComplyCube\Model;

use stdClass;

class WorkflowSession extends Model
{
    public ?string $id;
    public ?string $clientId;
    public ?string $entityName;
    public ?string $status;
    public ?bool $workflowTemplateId;
    public ?string $workflowTemplateName;
    public ?string $workflowTemplateDescription;
    public ?string $workflowId;
    public ?int $workflowVersion;
    public ?string $workflowDescription;
    public ?array $compliancePolicies;
    public ?string $outcome;
    public ?array $allRelatedChecks;
    public ?array $policyAssurance;
    public ?array $tasks;
    public ?string $lastCompletedTaskId;
    public ?string $createdAt;
    public ?string $completedAt;
    public ?string $updatedAt;
                        
    public function load(stdClass $response): void
    {
        parent::load($response);
    }
}
