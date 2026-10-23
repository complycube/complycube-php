<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class CompanyDetails extends Model
{
    public ?string $id;
    public ?string $name;
    public ?string $registrationNumber;
    public ?string $incorporationCountry;
    public ?string $incorporationDate;
    public ?string $dissolutionDate;
    public ?string $incorporationType;
    public ?Address $address;
    public ?bool $active;
    public ?string $sourceUrl;
    public ?array $owners;
    public ?array $officers;
    public ?array $filings;
    public ?array $industryCodes;
    public ?string $website;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->address = property_exists($response, "address")
            ? new Address($response->address)
            : null;

        $this->owners = property_exists($response, "owners")
            ? $this->toList($response->owners)
            : null;

        $this->officers = property_exists($response, "officers")
            ? $this->toList($response->officers)
            : null;

        $this->filings = property_exists($response, "filings")
            ? $this->toList($response->filings)
            : null;

        $this->industryCodes = property_exists($response, "industryCodes")
            ? $this->toList($response->industryCodes)
            : null;
    }

    private function toList($value): ?array
    {
        if (is_null($value) || is_array($value)) {
            return $value;
        }

        if (is_object($value)) {
            $items = get_object_vars($value);

            // A single entry returned as an object rather than a list
            if (empty($items) || !is_object(reset($items))) {
                return empty($items) ? [] : [$value];
            }

            return array_values($items);
        }

        return null;
    }
}
