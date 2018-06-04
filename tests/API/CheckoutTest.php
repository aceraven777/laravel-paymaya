<?php

namespace Aceraven777\PayMaya\Test\API;

use Aceraven777\PayMaya\API\Checkout;
use Aceraven777\PayMaya\Test\Model\Checkout\ItemTest;
use Aceraven777\PayMaya\Test\Model\Checkout\BuyerTest;
use Aceraven777\PayMaya\Test\Model\Checkout\ItemAmountTest;

class CheckoutTest extends \PHPUnit_Framework_TestCase
{
    public static function getObject()
    {
        $checkout = new Checkout();
        $checkout->buyer = BuyerTest::getObject();
        $checkout->items = [ItemTest::getObject()];
        $checkout->totalAmount = ItemAmountTest::getObject();
        $checkout->requestReferenceNumber = '123456789';
        $checkout->redirectUrl = [
            'success' => 'https://shop.com/success',
            'failure' => 'https://shop.com/failure',
            'cancel' => 'https://shop.com/cancel',
            ];
        $checkout->metadata = ['additional' => 'data'];

        return $checkout;
    }

    public function testInitialization()
    {
        $obj = self::getObject();
        $this->assertEquals($obj->buyer, BuyerTest::getObject());
        $this->assertEquals($obj->items, [ItemTest::getObject()]);
        $this->assertEquals($obj->totalAmount, ItemAmountTest::getObject());
        $this->assertEquals($obj->requestReferenceNumber, '123456789');
        $this->assertEquals($obj->redirectUrl, [
            'success' => 'https://shop.com/success',
            'failure' => 'https://shop.com/failure',
            'cancel' => 'https://shop.com/cancel',
            ]);

        return $obj;
    }

    /**
     * @depends testInitialization
     */
    public function testExecute($obj)
    {
        $obj->execute();
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->url);

        return $obj;
    }

    /**
     * @depends testExecute
     */
    public function testRetrieve($obj)
    {
        $obj->retrieve();
        $this->assertNotNull($obj->status);
        $this->assertNotNull($obj->paymentScheme);
        $this->assertNotNull($obj->paymentStatus);
        $this->assertNotNull($obj->metadata);
    }
}
