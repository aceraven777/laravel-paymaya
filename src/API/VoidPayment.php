<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Traits\ErrorHandler;
use Aceraven777\PayMaya\Core\CheckoutAPIManager;

class VoidPayment
{
    use ErrorHandler;

    public $id;

    public $checkoutId;
    public $reason;

    private $apiManager;

    public function __construct()
    {
        $this->apiManager = new CheckoutAPIManager();
    }

    public function execute()
    {
        $response = $this->apiManager->voidCheckout($this->checkoutId, ['reason' => $this->reason]);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = $responseArr['voidId'];

        return $responseArr;
    }
}
