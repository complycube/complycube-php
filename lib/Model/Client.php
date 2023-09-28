<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class Client extends Model
{
    public ?string $id;
    public ?string $type;
    public ?string $entityName;
    public ?string $email;
    public ?string $mobile;
    public ?string $telephone;
    public ?string $externalId;
    public ?string $joinedDate;
    public ?PersonDetails $personDetails;
    public ?CompanyDetails $companyDetails;
    public $metadata;
    protected ?Carbon $createdAt;
    protected ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        if (property_exists($response, "personDetails")) {
            $this->personDetails = new PersonDetails($response->personDetails);
            $this->companyDetails = null;
        }

        if (property_exists($response, "companyDetails")) {
            $this->companyDetails = new CompanyDetails(
                $response->companyDetails
            );
            $this->personDetails = null;
        }

        $this->metadata = property_exists($response, "metadata")
            ? $response->metadata
            : null;
    }
}
