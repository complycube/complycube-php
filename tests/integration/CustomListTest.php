<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\CustomList;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \ComplyCube\Resources\CustomListApi
 */
class CustomListTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    private ?array $personEntity;
    private ?array $companyEntity;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }

        $this->personEntity = [
            "entityType" => "person",
            "names" => [
                [
                    "type" => "primary",
                    "firstName" => "John",
                    "lastName" => "Smith",
                ],
                [
                    "type" => "alias",
                    "firstName" => "John",
                    "lastName" => "Smyth",
                ],
            ],
            "active" => true,
            "gender" => "male",
            "countries" => [
                [
                    "type" => "Jurisdiction",
                    "country" => "US",
                ],
                [
                    "type" => "Resident of",
                    "country" => "GB",
                ],
            ],
            "dates" => [
                [
                    "type" => "Date of Birth",
                    "date" => [
                        "day" => "01",
                        "month" => "01",
                        "year" => "1987",
                    ],
                ],
            ],
            "images" => ["https://www.example.com/johnDoe.jpg"],
        ];

        $this->companyEntity = [
            "entityType" => "company",
            "names" => [
                [
                    "type" => "primary",
                    "companyName" => "Company A",
                ],
                [
                    "type" => "alias",
                    "companyName" => "Company 1",
                ],
            ],
            "active" => true,
            "countries" => [
                [
                    "type" => "Jurisdiction",
                    "country" => "US",
                ],
                [
                    "type" => "Resident of",
                    "country" => "GB",
                ],
            ],
            "dates" => [
                [
                    "type" => "Date of Registration",
                    "date" => [
                        "day" => "01",
                        "month" => "01",
                        "year" => "1987",
                    ],
                ],
            ],
            "images" => ["https://www.example.com/johnDoe.jpg"],
        ];
    }

    private function custom_list_assertions($item)
    {
        $this->assertInstanceOf(CustomList::class, $item);

        foreach (["id", "name", "stats"] as $key) {
            $this->assertNotNull($item->$key);
        }

        foreach (["personCount", "companyCount"] as $key) {
            $this->assertNotNull($item->stats->$key);
        }

        foreach (["createdAt", "updatedAt", "lastActionBy"] as $property_name) {
            $item_reflection_property = new ReflectionProperty(
                $item,
                $property_name
            );

            $item_reflection_property->setAccessible(true);

            $this->assertNotNull($item_reflection_property->getValue($item));
        }

        $this->assertNull($item->description);
    }

    /**
     * @group live
     */
    public function testGetNonExistentCustomList()
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->customLists()->get("non existent list id");
    }

    /**
     * @group live
     */
    public function testGetCustomList(): string
    {
        $result = $this->complycube
            ->customLists()
            ->get("6286d8c68934e300099ee74f");

        $this->custom_list_assertions($result);

        return $result->id;
    }

    /**
     * @group live
     * @depends testGetCustomList
     */
    public function testAddPersonEntityToCustomList($id)
    {
        $result = $this->complycube->customLists()->get($id);

        $this->complycube->customLists()->add($id, $this->personEntity);

        $this->assertEquals(
            $result->stats->personCount + 1,
            $this->complycube->customLists()->get($id)->stats->personCount
        );
    }

    /**
     * @group live
     * @depends testGetCustomList
     */
    public function testAddInvalidPersonEntityToCustomList($id)
    {
        $this->expectException(ComplyCubeClientException::class);

        unset($this->personEntity["names"]);

        $this->complycube->customLists()->add($id, $this->personEntity);
    }

    /**
     * @group live
     * @depends testGetCustomList
     */
    public function testAddCompanyEntityToCustomList($id)
    {
        $result = $this->complycube->customLists()->get($id);

        $this->complycube->customLists()->add($id, $this->companyEntity);

        $this->assertEquals(
            $result->stats->companyCount + 1,
            $this->complycube->customLists()->get($id)->stats->companyCount
        );
    }

    /**
     * @group live
     * @depends testGetCustomList
     */
    public function testAddInvalidCompanyEntityToCustomList($id)
    {
        $this->expectException(ComplyCubeClientException::class);

        unset($this->companyEntity["names"]);

        $this->complycube->customLists()->add($id, $this->companyEntity);
    }

    /**
     * @group live
     */

    public function testListCustomLists()
    {
        $result = $this->complycube->customLists()->list();

        $this->assertInstanceOf(ComplyCubeCollection::class, $result);

        $this->assertCount(1, $result);

        foreach ($result->items as $item) {
            $this->custom_list_assertions($item);
        }
    }
}
