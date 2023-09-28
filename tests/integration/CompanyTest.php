<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\CompanyDetails;
use ComplyCube\Model\ComplyCubeCollection;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \ComplyCube\Resources\CompanyApi
 */
class CompanyTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    public function testSearchCompany(): void
    {
        $result = $this->complycube->companies()->search([
            "companyName" => "CLEDARA",
            "incorporationCountry" => "GB",
        ]);

        $this->assertInstanceOf(ComplyCubeCollection::class, $result);

        $this->assertCount(2, $result);

        foreach ($result->items as $item) {
            $this->assertInstanceOf(CompanyDetails::class, $item);

            foreach (
                [
                    "id",
                    "name",
                    "registrationNumber",
                    "incorporationCountry",
                    "incorporationDate",
                    "incorporationType",
                    "address",
                    "active",
                    "sourceUrl",
                ]
                as $key
            ) {
                $this->assertNotNull($item->$key);
            }

            foreach (["line", "city", "postalCode", "country"] as $key) {
                $this->assertNotNull($item->address->$key);
            }

            foreach (["createdAt", "updatedAt"] as $property_name) {
                $item_reflection_property = new ReflectionProperty(
                    $item,
                    $property_name
                );

                $address_reflection_property = new ReflectionProperty(
                    $item->address,
                    $property_name
                );

                $item_reflection_property->setAccessible(true);
                $address_reflection_property->setAccessible(true);

                $this->assertNotNull(
                    $item_reflection_property->getValue($item)
                );

                $this->assertNull(
                    $address_reflection_property->getValue($item->address)
                );
            }

            foreach (
                ["owners", "officers", "filings", "industryCodes", "website"]
                as $key
            ) {
                $this->assertNull($item->$key);
            }
        }
    }

    public function testSearchCompanyWithInvalidParameters(): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->companies()->search([
            "param_1" => "CLEDARA",
            "param_2" => "GB",
        ]);
    }

    public function testGetCompanyDetails(): void
    {
        $result = $this->complycube
            ->companies()
            ->get("67625f5f3131343535333733");

        $this->assertInstanceOf(CompanyDetails::class, $result);

        foreach (
            [
                "id",
                "name",
                "registrationNumber",
                "incorporationCountry",
                "incorporationDate",
                "incorporationType",
                "address",
                "active",
                "sourceUrl",
                "owners",
                "officers",
                "filings",
                "industryCodes",
            ]
            as $key
        ) {
            $this->assertNotNull($result->$key);
        }

        foreach (["line", "city", "postalCode", "country"] as $key) {
            $this->assertNotNull($result->address->$key);
        }

        foreach (
            [
                "owners" => [
                    "type",
                    "role",
                    "entityType",
                    "entityName",
                    "ownershipType",
                    "firstName",
                    "lastName",
                    "dob",
                    "nationality",
                    "countryOfResidence",
                    "address",
                    "ownershipStructure",
                ],
                "officers" => [
                    "entityName",
                    "role",
                    "occupation",
                    "nationality",
                    "firstName",
                    "lastName",
                    "active",
                ],
                "filings" => ["name", "description", "type", "date", "fileUrl"],
                "industryCodes" => ["code", "description", "scheme"],
            ]
            as $key => $value
        ) {
            foreach ($result->$key as $item) {
                foreach ($value as $attribute) {
                    $this->assertTrue(isset($item->$attribute));
                }
            }
        }

        foreach (["createdAt", "updatedAt"] as $property_name) {
            $result_reflection_property = new ReflectionProperty(
                $result,
                $property_name
            );

            $address_reflection_property = new ReflectionProperty(
                $result->address,
                $property_name
            );

            $result_reflection_property->setAccessible(true);
            $address_reflection_property->setAccessible(true);

            $this->assertNotNull(
                $result_reflection_property->getValue($result)
            );

            $this->assertNull(
                $address_reflection_property->getValue($result->address)
            );
        }

        $this->assertNull($result->website);
    }

    public function testGetNonExistentCompanyDetails(): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->companies()->get("non existent company id");
    }
}
