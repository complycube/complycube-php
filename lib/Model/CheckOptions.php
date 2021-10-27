<?php

namespace ComplyCube\Model;

use \stdClass;

class ScreeningListsScope implements \JsonSerializable
{
    public string $mode;
    public iterable $lists;

    public function jsonSerialize()
    {
        return array_filter([
            'mode' => $this->mode,
            'lists' => $this->lists
        ], function ($value) {
            return ($value !== null);
        });
    }
}

class CheckOptions implements \JsonSerializable
{
    public ?ScreeningListsScope $screeningListsScope;
    public ?string $screeningNameSearchMode;
    public ?iterable $screeningClassification;
    public ?iterable $analysisCoverage;
    public ?int $minimumPermittedAge;
    public ?bool $clientDataValidation;

    public function jsonSerialize()
    {
        return array_filter([
            'screeningListsScope' => $this->screeningListsScope,
            'screeningNameSearchMode' => $this->screeningNameSearchMode,
            'screeningClassification' => $this->screeningClassification,
            'analysisCoverage' => $this->analysisCoverage,
            'minimumPermittedAge' => $this->minimumPermittedAge,
            'clientDataValidation' => $this->clientDataValidation
        ], function ($value) {
            return ($value !== null);
        });
    }
}