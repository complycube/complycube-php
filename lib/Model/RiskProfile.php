<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use stdClass;

class RiskProfile extends Model
{
    public ?string $overall;
    public ?CountryRisk $countryRisk;
    public ?PoliticalExposureRisk $politicalExposureRisk;
    public ?OccupationRisk $occupationRisk;
    public ?WatchlistRisk $watchlistRisk;
    public ?Carbon $updatedAt;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->countryRisk = property_exists($response, "countryRisk")
            ? new CountryRisk($response->countryRisk)
            : null;

        $this->politicalExposureRisk = property_exists(
            $response,
            "politicalExposureRisk"
        )
            ? new PoliticalExposureRisk($response->politicalExposureRisk)
            : null;

        $this->occupationRisk = property_exists($response, "occupationRisk")
            ? new OccupationRisk($response->occupationRisk)
            : null;

        $this->watchlistRisk = property_exists($response, "watchlistRisk")
            ? new WatchlistRisk($response->watchlistRisk)
            : null;
    }
}

class CountryRisk extends Model
{
    public ?string $risk;
    public ?string $country;
    public ?array $breakdown;

    public function load(stdClass $response): void
    {
        parent::load($response);

        $this->breakdown = property_exists($response, "breakdown")
            ? $response->breakdown
            : null;
    }
}

class PoliticalExposureRisk extends Model
{
    public ?string $risk;
    public ?string $checkId;
}

class OccupationRisk extends Model
{
    public ?string $risk;
    public ?string $checkId;
    public ?string $occupationCategory;
    public ?string $occupationTitle;
}

class WatchlistRisk extends Model
{
    public ?string $risk;
    public ?string $checkId;
}
