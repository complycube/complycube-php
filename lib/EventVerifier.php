<?php

namespace ComplyCube;

class EventVerifier
{
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Validate events sent to your webhooks.
     *
     * @param string $responseBody the received event
     * @param string $expectedHash the received event signature
     * @return void
     */
    public function constructEvent(string $responseBody, string $expectedHash)
    {
        $calculatedHash = hash_hmac("sha256", $responseBody, $this->secret);
        if (!hash_equals($calculatedHash, $expectedHash)) {
            throw new \ComplyCube\Exception\VerificationException('Invalid signature for event');
        }
        $event = new \ComplyCube\Model\Event();
        $event->load(json_decode($responseBody));
        return $event;
    }
}
