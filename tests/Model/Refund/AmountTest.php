<?php

namespace Aceraven777\PayMaya\Test\Model\Refund;

use Aceraven777\PayMaya\Model\Refund\Amount;

class AmountTest extends \PHPUnit_Framework_TestCase
{
    public static function getObject()
    {
        $refundAmount = new Amount();
        $refundAmount->currency = 'PHP';
        $refundAmount->value = '69.00';

        return $refundAmount;
    }

    public function testInitialization()
    {
        $obj = self::getObject();
        $this->assertEquals($obj->currency, 'PHP');
        $this->assertEquals($obj->value, '69.00');
    }
}
