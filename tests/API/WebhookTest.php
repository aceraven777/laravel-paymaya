<?php

namespace Aceraven777\PayMaya\Test\API;

use Aceraven777\PayMaya\API\Webhook;

class WebhookTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        self::clearWebhooks();
    }

    /**
     * Clear all webhooks.
     * @return void
     */
    public static function clearWebhooks()
    {
        $webhooks = Webhook::retrieve();
        foreach ($webhooks as $webhook) {
            $webhook->delete();
        }
    }

    public static function getObject($webhookName, $callbackUrl)
    {
        $webhook = new Webhook();
        $webhook->name = $webhookName;
        $webhook->callbackUrl = $callbackUrl;

        return $webhook;
    }

    public function testSuccessWebhook()
    {
        $webhookName = Webhook::CHECKOUT_SUCCESS;
        $callbackUrl = 'http://shop.someserver.com/success';

        $obj = self::getObject($webhookName, $callbackUrl);
        $this->assertEquals($obj->name, $webhookName);
        $this->assertEquals($obj->callbackUrl, $callbackUrl);

        return $obj;
    }

    public function testFailureWebhook()
    {
        $webhookName = Webhook::CHECKOUT_FAILURE;
        $callbackUrl = 'http://shop.someserver.com/failure';

        $obj = self::getObject($webhookName, $callbackUrl);
        $this->assertEquals($obj->name, $webhookName);
        $this->assertEquals($obj->callbackUrl, $callbackUrl);

        return $obj;
    }

    public function testDropoutWebhook()
    {
        $webhookName = Webhook::CHECKOUT_DROPOUT;
        $callbackUrl = 'http://shop.someserver.com/dropout';

        $obj = self::getObject($webhookName, $callbackUrl);
        $this->assertEquals($obj->name, $webhookName);
        $this->assertEquals($obj->callbackUrl, $callbackUrl);

        return $obj;
    }

    /**
     * @depends testSuccessWebhook
     * @depends testFailureWebhook
     * @depends testDropoutWebhook
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
}
