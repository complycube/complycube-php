<?php

namespace ComplyCube\Model;

class AuditDiff implements \JsonSerializable
{
    public $action;
    public $path = [];
    public $old;
    public $new;

    public function __construct($aDiff)
    {
        $this->action = $aDiff->action;
        $this->path = $aDiff->path;
        $this->old = isset($aDiff->old) ? $aDiff->old : null;
        $this->new = isset($aDiff->new) ? $aDiff->new : null;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'action' => $this->action,
            'path' => $this->path,
            'old' => $this->old,
            'new' => $this->new
        ], function ($value) {
            return ($value !== null);
        });
    }
}
