<?php

namespace ComplyCube\Model;

use stdClass;

class ScreeningListsScope extends Model
{
    public ?string $mode;
    public ?iterable $lists;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->lists = property_exists($response, "lists")
            ? $response->lists
            : null;
    }
}

class CheckOptions extends Model
{
    public ?ScreeningListsScope $screeningListsScope;
    public ?string $screeningNameSearchMode;
    public ?iterable $screeningClassification;
    public ?iterable $analysisCoverage;
    public ?int $minimumPermittedAge;
    public ?bool $clientDataValidation;

    public function load(stdClass $response): void
    {
        $this->screeningListsScope = property_exists(
            $response,
            "screeningListsScope"
        )
            ? new ScreeningListsScope($response->screeningListsScope)
            : null;

        $this->screeningClassification = property_exists(
            $response,
            "screeningClassification"
        )
            ? $response->screeningClassification
            : null;

        $this->analysisCoverage = property_exists($response, "analysisCoverage")
            ? $response->analysisCoverage
            : null;
    }
}
