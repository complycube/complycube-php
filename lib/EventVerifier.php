<?php

namespace ComplyCube;

use ComplyCube\Exception\VerificationException;
use ComplyCube\Model\Event;

class EventVerifier
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Validate events sent to your webhooks.
     *
     * @param string $responseBody the received event
     * @param string $expectedHash the received event signature
     * @return Event
     */
    public function constructEvent(string $responseBody, string $expectedHash)
    {
        $calculatedHash = hash_hmac("sha256", $responseBody, $this->secret);
        if (!hash_equals($calculatedHash, $expectedHash)) {
            throw new VerificationException("Invalid signature for event");
        }
        $event = new Event();
        $event->load(json_decode($responseBody));
        return $event;
    }
}
