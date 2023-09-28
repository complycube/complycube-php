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
            ? $response->owners
            : null;

        $this->officers = property_exists($response, "officers")
            ? $response->officers
            : null;

        $this->filings = property_exists($response, "filings")
            ? $response->filings
            : null;

        $this->industryCodes = property_exists($response, "industryCodes")
            ? $response->industryCodes
            : null;
    }
}
