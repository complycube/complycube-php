<?php

require_once __DIR__ . '/vendor/autoload.php';

use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\Event;
use ComplyCube\EventVerifier;
use ComplyCube\Exception\VerificationException;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Complycube-Signature, X-Requested-With");

$data = file_get_contents('php://input');
$verifier = new \ComplyCube\EventVerifier('COMPLYCUBE_WEBHOOK_SECRET');
$headers = apache_request_headers();
define("SIGNATURE_KEY", 'Complycube-Signature');

try {
    if (!isset($headers[SIGNATURE_KEY])) {
        http_response_code(400);
        return;
    }
    $event = $verifier->constructEvent($data, $headers[SIGNATURE_KEY]);
    switch ($event->type) {
        case "check.completed":
            $outcome = $event->payload->outcome;
            # do completed check processing
            break;
        case "check.pending":
            # do pending check processing
            break;
        default:
            http_response_code(400);
            return;
    }
    http_response_code(200);
    return;
} catch (\ComplyCube\Exception\VerificationException $e) {
    http_response_code(400);
    return;
}
