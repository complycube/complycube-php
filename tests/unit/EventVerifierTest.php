<?php

namespace ComplyCube\Tests\Unit;

use ComplyCube\EventVerifier;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComplyCube\EventVerifier
 */
class EventVerifierTest extends TestCase
{
    public function testNullResponse()
    {
        $this->expectException(\TypeError::class);
        $ev = new EventVerifier("a_webhook_signature");
        $ev->constructEvent(null, "an_event_signature");
    }

    public function testInvalidSignatureEvent()
    {
        $this->expectException(
            \ComplyCube\Exception\VerificationException::class,
        );
        $ev = new EventVerifier("a_webhook_signature");
        $ev->constructEvent("a_response", "an_event_signature");
    }

    public function testValidSignatureEvent()
    {
        $resp = "{\"id\":\"value\"}";
        $ev = new EventVerifier("a_webhook_signature");
        $e = $ev->constructEvent(
            $resp,
            "bc00414fa4d54277a3ed01e5ee258d9800a918a7791c7cda16d38b82f38f2150",
        );
        $this->assertEquals("value", $e->id);
    }
}
