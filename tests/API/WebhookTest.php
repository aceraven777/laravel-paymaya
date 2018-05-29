<?php

namespace Aceraven777\PayMaya\Test\API;

use Aceraven777\PayMaya\API\Webhook;

class WebhookTest extends \PHPUnit_Framework_TestCase
{
    public static function getObject()
    {
        $webhook = new Webhook();
        $webhook->name = Webhook::CHECKOUT_SUCCESS;
        $webhook->callbackUrl = 'http://shop.someserver.com/success';

        return $webhook;
    }

    public function testInitialization()
    {
        $this->clearWebhooks();
        $obj = self::getObject();
        $this->assertEquals($obj->name, Webhook::CHECKOUT_SUCCESS);
        $this->assertEquals($obj->callbackUrl, 'http://shop.someserver.com/success');

        return $obj;
    }

    /**
     * @depends testInitialization
     */
    public function testRegister($obj)
    {
        $obj->register();
        $this->assertNotNull($obj->id);

        return $obj;
    }

    /**
     * @depends testRegister
     */
    public function testRetrieve($obj)
    {
        $webhooks = Webhook::retrieve();
        $this->assertNotEmpty($webhooks);
    }

    /**
     * @depends testRegister
     */
    public function testUpdate($obj)
    {
        $obj->callbackUrl = 'http://shop.someserver.com/successUpdated';
        $obj->update();
        $this->assertEquals($obj->callbackUrl, 'http://shop.someserver.com/successUpdated');

        return $obj;
    }

    /**
     * @depends testUpdate
     */
    public function testDelete($obj)
    {
        $obj->delete();
        $this->assertNull($obj->id);
        $this->assertNull($obj->name);
        $this->assertNull($obj->callbackUrl);
    }

    /**
     * Clear all webhooks.
     * @return void
     */
    protected function clearWebhooks()
    {
        $webhooks = Webhook::retrieve();
        foreach ($webhooks as $webhook) {
            $webhook->delete();
        }
    }
}
