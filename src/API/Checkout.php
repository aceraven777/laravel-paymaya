<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Core\CheckoutAPIManager;
use Aceraven777\PayMaya\Traits\ErrorHandler;
use PayMaya\API\Checkout as PayMayaCheckout;

class Checkout extends PayMayaCheckout
{
    use ErrorHandler;

    public $id;
    public $paymentScheme;
    public $status;

    public function __construct()
    {
        $this->apiManager = new CheckoutAPIManager();
    }

    public function execute()
    {
        $checkoutInformation = json_decode(json_encode($this), true);
        $response = $this->apiManager->initiateCheckout($checkoutInformation);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = $responseArr['checkoutId'];
        $this->url = $responseArr['redirectUrl'];

        return $responseArr;
    }

    public function retrieve()
    {
        $response = $this->apiManager->retrieveCheckout($this->id);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->status = $responseArr['status'];
        $this->paymentScheme = $responseArr['paymentScheme'];
        $this->requestReferenceNumber = $responseArr['requestReferenceNumber'];
        $this->transactionReferenceNumber = $responseArr['transactionReferenceNumber'];
        $this->receiptNumber = $responseArr['receiptNumber'];
        $this->paymentStatus = $responseArr['paymentStatus'];

        if (isset($responseArr['metadata'])) {
            $this->metadata = $responseArr['metadata'];
        }

        return $responseArr;
    }
}
