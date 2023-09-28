<?php

namespace ComplyCube\Tests\Unit;

use ComplyCube\ApiClient;
use ComplyCube\ApiResource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\ApiResource
 */
class ApiResourceTest extends TestCase
{
    protected $newAnonymousClassFromAbstract;

    protected function setUp(): void
    {
        $a_client = new ApiClient("");
        $this->newAnonymousClassFromAbstract = new class ($a_client, "a\class")
            extends ApiResource
        {
            public function returnThis()
            {
                return $this;
            }
        };
    }

    public function testAbstractClassType()
    {
        $this->assertInstanceOf(
            ApiResource::class,
            $this->newAnonymousClassFromAbstract->returnThis(),
        );
    }
}
