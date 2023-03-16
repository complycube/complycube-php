<?php

namespace ComplyCube\Model;

class AuditLog implements \JsonSerializable
{
    public ?string $id = null;
    public ?string $member = null;
    public ?string $resourceType = null;
    public ?string $clientId = null;
    public ?string $trigger = null;
    public ?string $action = null;
    public $createdAt = null;
    public $diff = array();

    public function load(\stdClass $response)
    {
        $this->id = $response->id;
        $this->member = isset($response->member) ? $response->member : null;
        $this->resourceType = $response->resourceType;
        $this->clientId = $response->clientId;
        $this->trigger = $response->trigger;
        $this->action = $response->action;
        $this->createdAt = new \DateTime($response->createdAt);
        if (isset($response->diff)) {
            foreach ($response->diff as $aDiff) {
                $diff[] = new AuditDiff($aDiff);
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'id' => $this->id,
            'member' => $this->member,
            'resourceType' => $this->resourceType,
            'clientId' => $this->clientId,
            'trigger' => $this->trigger,
            'action' => $this->action,
            'createdAt' => $this->createdAt,
            
        ], function ($value) {
            return ($value !== null);
        });
    }
}
