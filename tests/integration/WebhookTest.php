<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\Model\Webhook;

/**
 * @covers \ComplyCube\Resources\WebhookApi
 */
class WebhookTest extends \PHPUnit\Framework\TestCase
{
    private $personClient;
    private $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv('CC_API_KEY');
            $this->complycube = new \ComplyCube\ComplyCubeClient($apiKey);
        }
    }

    public function testCreateWebhook($enabled = false): Webhook
    {
        $webhook = new Webhook();
        $webhook->url = 'http://hook.com/';
        $webhook->enabled = $enabled;
        $webhook->events = ['check.pending'];
        $result = $this->complycube->webhooks()->create($webhook);
        $this->assertEquals($webhook->url, $result->url);
        $this->assertEquals($webhook->enabled, $result->enabled);
        $this->assertEquals($webhook->events[0], $result->events[0]);
        $this->assertNotNull($result->secret);
        return $result;
    }

    /**
    * @depends testCreateWebhook
    */
    public function testUpdateWebhookInline(Webhook $webhook): void
    {
        $result = $this->complycube->webhooks()->update(
            $webhook->id,
            ['url' => 'https://newurl/endpoint', 'enabled' => false]
        );
        $this->assertEquals('https://newurl/endpoint', $result->url);
    }

    /**
    * @depends testCreateWebhook
    */
    public function testUpdateWebhook(Webhook $webhook): void
    {
        $newWebhook = $webhook;
        $newWebhook->url = 'https://newurl/endpoint';
        $result = $this->complycube->webhooks()->update($webhook->id, $newWebhook);
        $this->assertEquals($newWebhook->url, $result->url);
    }

    /**
    * @depends testCreateWebhook
    */
    public function testGetWebhook(Webhook $webhook): void
    {
        $result = $this->complycube->webhooks()->get($webhook->id);
        $this->assertEquals($webhook->id, $result->id);
    }

    public function testListWebhooks()
    {
        $this->testCreateWebhook();
        $hooks = $this->complycube->webhooks()->list();
        $this->assertGreaterThan(0, $hooks->totalItems);
    }

    public function testList2HooksOnly()
    {
        $this->testCreateWebhook();
        $this->testCreateWebhook();
        $this->testCreateWebhook(true);
        $hooks = $this->complycube->webhooks()->list(['page' => 1, 'pageSize' => 2]);
        $this->assertEquals(2, iterator_count($hooks));
    }

    public function testFilterEnabledHooksOnly()
    {
        $hooks = $this->complycube->webhooks()->list(['enabled' => 'true']);
        foreach ($hooks as $hook) {
            $this->assertEquals(true, $hook->enabled);
        }
    }

    /**
    * @depends testCreateWebhook
    */
    public function testDeleteWebhook(Webhook $webhook): void
    {
        $this->expectException(\ComplyCube\Exception\ComplyCubeClientException::class);
        $this->complycube->webhooks()->delete($webhook->id);
        $this->complycube->webhooks()->get($webhook->id);
    }

    public function testClearWebhooks()
    {
        $hooks = $this->complycube->webhooks()->list();
        foreach ($hooks as $hook) {
            sleep(3);
            $this->complycube->webhooks()->delete($hook->id);
        }
        $hooks = $this->complycube->webhooks()->list();
        $this->assertEquals(0, $hooks->totalItems);
    }
}
