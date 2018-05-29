<?php

namespace Aceraven777\PayMaya\Test\Model\Checkout;

use Aceraven777\PayMaya\Model\Checkout\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public static function getObject()
    {
        $item = new Item();
        $item->name = 'Leather Belt';
        $item->code = 'pm_belt';
        $item->description = 'Medium-sized belt made from authentic leather';
        $item->quantity = '1';
        $item->amount = ItemAmountTest::getObject();
        $item->totalAmount = ItemAmountTest::getObject();

        return $item;
    }

    public function testInitialization()
    {
        $obj = self::getObject();
        $this->assertEquals($obj->name, 'Leather Belt');
        $this->assertEquals($obj->code, 'pm_belt');
        $this->assertEquals($obj->description, 'Medium-sized belt made from authentic leather');
        $this->assertEquals($obj->quantity, '1');
        $this->assertEquals($obj->amount, ItemAmountTest::getObject());
        $this->assertEquals($obj->totalAmount, ItemAmountTest::getObject());
    }
}
