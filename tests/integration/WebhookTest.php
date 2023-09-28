<?php

namespace ComplyCube\Tests\Integration;

use ComplyCube\ComplyCubeClient;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\Webhook;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \ComplyCube\Resources\WebhookApi
 */
class WebhookTest extends TestCase
{
    private ?ComplyCubeClient $complycube;

    protected function setUp(): void
    {
        if (empty($this->complycube)) {
            $apiKey = getenv("CC_API_KEY");
            $this->complycube = new ComplyCubeClient($apiKey);
        }
    }

    private function webhook_assertions($item)
    {
        $this->assertInstanceOf(Webhook::class, $item);

        $this->assertNotNull($item->id);

        foreach ($item->events as $value) {
            $this->assertIsString($value);
        }

        foreach (["createdAt", "updatedAt"] as $property_name) {
            $item_reflection_property = new ReflectionProperty(
                $item,
                $property_name
            );

            $item_reflection_property->setAccessible(true);

            $this->assertNotNull($item_reflection_property->getValue($item));
        }

        $this->assertNull($item->description);
    }

    public function testCreateWebhook($enabled = false): Webhook
    {
        $webhook = new Webhook([
            "url" => "http://hook.com/",
            "enabled" => $enabled,
            "events" => ["check.pending"],
        ]);
        $result = $this->complycube->webhooks()->create($webhook);
        $this->webhook_assertions($result);
        $this->assertNotNull($result->secret);
        $this->assertEquals($webhook->url, $result->url);
        $this->assertEquals($webhook->enabled, $result->enabled);
        $this->assertEquals($webhook->events[0], $result->events[0]);
        return $result;
    }

    /**
     * @depends testCreateWebhook
     */
    public function testUpdateWebhookInline(Webhook $webhook): void
    {
        $result = $this->complycube->webhooks()->update($webhook->id, [
            "url" => "https://newurl/endpoint",
            "enabled" => false,
        ]);
        $this->webhook_assertions($result);
        $this->assertNull($result->secret);
        $this->assertEquals("https://newurl/endpoint", $result->url);
    }

    /**
     * @depends testCreateWebhook
     */
    public function testUpdateWebhook(Webhook $webhook): void
    {
        $newWebhook = $webhook;
        $newWebhook->url = "https://newurl/endpoint";
        $result = $this->complycube
            ->webhooks()
            ->update($webhook->id, $newWebhook);
        $this->webhook_assertions($result);
        $this->assertNull($result->secret);
        $this->assertEquals($newWebhook->url, $result->url);
    }

    /**
     * @depends testCreateWebhook
     */
    public function testGetWebhook(Webhook $webhook): void
    {
        $result = $this->complycube->webhooks()->get($webhook->id);
        $this->webhook_assertions($result);
        $this->assertNull($result->secret);
        $this->assertEquals($webhook->id, $result->id);
    }

    public function testListWebhooks()
    {
        $this->testCreateWebhook();
        $hooks = $this->complycube->webhooks()->list();
        $this->assertInstanceOf(ComplyCubeCollection::class, $hooks);
        $this->assertGreaterThan(0, $hooks->totalItems);
        foreach ($hooks as $hook) {
            $this->webhook_assertions($hook);
            $this->assertNull($hook->secret);
        }
    }

    public function testList2HooksOnly()
    {
        $this->testCreateWebhook();
        $this->testCreateWebhook();
        $this->testCreateWebhook(true);
        $hooks = $this->complycube
            ->webhooks()
            ->list(["page" => 1, "pageSize" => 2]);
        $this->assertInstanceOf(ComplyCubeCollection::class, $hooks);
        $this->assertEquals(2, iterator_count($hooks));
        foreach ($hooks as $hook) {
            $this->webhook_assertions($hook);
            $this->assertNull($hook->secret);
        }
    }

    public function testFilterEnabledHooksOnly()
    {
        $hooks = $this->complycube->webhooks()->list(["enabled" => "true"]);
        $this->assertInstanceOf(ComplyCubeCollection::class, $hooks);
        foreach ($hooks as $hook) {
            $this->webhook_assertions($hook);
            $this->assertNull($hook->secret);
            $this->assertEquals(true, $hook->enabled);
        }
    }

    /**
     * @depends testCreateWebhook
     */
    public function testDeleteWebhook(Webhook $webhook): void
    {
        $this->expectException(ComplyCubeClientException::class);
        $this->complycube->webhooks()->delete($webhook->id);
        $this->complycube->webhooks()->get($webhook->id);
    }

    public function testClearWebhooks()
    {
        $hooks = $this->complycube->webhooks()->list();
        $this->assertInstanceOf(ComplyCubeCollection::class, $hooks);
        foreach ($hooks as $hook) {
            $this->webhook_assertions($hook);
            $this->assertNull($hook->secret);
            sleep(3);
            $this->complycube->webhooks()->delete($hook->id);
        }
        $hooks = $this->complycube->webhooks()->list();
        $this->assertInstanceOf(ComplyCubeCollection::class, $hooks);
        $this->assertEquals(0, $hooks->totalItems);
    }
}
