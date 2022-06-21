<?php

namespace Aceraven777\PayMaya\Core;

use PayMaya\Core\CheckoutAPIManager as PayMayaCheckoutAPIManager;
use PayMaya\Core\HTTPConfig;
use PayMaya\Core\HTTPConnection;

class CheckoutAPIManager extends PayMayaCheckoutAPIManager
{
    public function voidCheckout($checkoutId, $data)
    {
        $this->useBasicAuthWithApiKey($this->secretApiKey);
        $httpConfig = new HTTPConfig($this->baseUrl.'/v1/checkouts/'.$checkoutId,
                                     'DELETE',
                                     $this->httpHeaders
                                     );
        $httpConnection = new HTTPConnection($httpConfig);
        $payload = json_encode($data);
        $response = $httpConnection->execute($payload);

        return $response;
    }

    public function refundCheckout($checkoutId, $data)
    {
        $this->useBasicAuthWithApiKey($this->secretApiKey);
        $httpConfig = new HTTPConfig(
            $this->baseUrl.'/v1/checkouts/'.$checkoutId.'/refunds',
            'POST',
            $this->httpHeaders
        );
        $httpConnection = new HTTPConnection($httpConfig);
        $payload = json_encode($data);
        $response = $httpConnection->execute($payload);

        return $response;
    }

    public function retrieveRefunds($checkoutId)
    {
        $this->useBasicAuthWithApiKey($this->secretApiKey);
        $httpConfig = new HTTPConfig(
            $this->baseUrl.'/v1/checkouts/'.$checkoutId.'/refunds',
            'GET',
            $this->httpHeaders
        );
        $httpConnection = new HTTPConnection($httpConfig);
        $response = $httpConnection->execute(null);

        return $response;
    }

    public function retrieveRefundInfo($checkoutId, $refundId)
    {
        $this->useBasicAuthWithApiKey($this->secretApiKey);
        $httpConfig = new HTTPConfig(
            $this->baseUrl.'/v1/checkouts/'.$checkoutId.'/refunds/'.$refundId,
            'GET',
            $this->httpHeaders
        );
        $httpConnection = new HTTPConnection($httpConfig);
        $response = $httpConnection->execute(null);

        return $response;
    }
}
