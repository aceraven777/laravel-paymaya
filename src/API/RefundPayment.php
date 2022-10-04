<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Core\CheckoutAPIManager;
use Aceraven777\PayMaya\Traits\ErrorHandler;

class RefundPayment
{
    use ErrorHandler;

    public $id;

    public $checkoutId;
    public $reason;
    public $amount;

    public $refundId;

    private $apiManager;

    public function __construct()
    {
        $this->apiManager = new CheckoutAPIManager();
    }

    public function execute()
    {
        // Filter array to only 'reason', 'amount' keys
        $data = array_intersect_key(
            json_decode(json_encode($this), true),
            array_flip(['reason', 'amount'])
        );

        $response = $this->apiManager->refundCheckout($this->checkoutId, $data);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = $responseArr['refundId'];

        return $responseArr;
    }

    public function retrieve()
    {
        $response = $this->apiManager->retrieveRefunds($this->checkoutId);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        return $responseArr;
    }

    public function retrieveInfo()
    {
        $response = $this->apiManager->retrieveRefundInfo($this->checkoutId, $this->refundId);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        return $responseArr;
    }
}
