<?php

namespace ComplyCube;

use \stdClass;

class ApiResponse
{
    /** @var integer */
    protected $httpStatusCode;
    /** @var string */
    protected $body;
    /** @var stdClass */
    protected $decodedBody;
    /** @var array<string> */
    protected $headers;

    /**
     * Api response.
     *
     * @param integer $httpStatusCode
     * @param string|null $body
     * @param array $headers
     */
    public function __construct(int $httpStatusCode, ?string $body = null, array $headers = [])
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->body = $body;
        $this->headers = $headers;
        $this->decodeBody();
    }

    /**
     * Api response status code.
     *
     * @return integer
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Api response as string representation.
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Api response decoded to php object.
     *
     * @return stdClass
     */
    public function getDecodedBody(): stdClass
    {
        return $this->decodedBody;
    }

    /**
     * Api response headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Decode json body of reponse to php object.
     *
     * @return void
     */
    private function decodeBody(): void
    {
        if (empty($this->body) || $this->body === null) {
            $this->decodedBody = (objecT)[];
            return;
        }

        $this->decodedBody = json_decode($this->body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \JsonException('The body of the response could not be decoded as JSON.');
        }
    }
}
