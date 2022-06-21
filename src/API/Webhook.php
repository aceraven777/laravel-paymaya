<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Core\CheckoutAPIManager;
use Aceraven777\PayMaya\Traits\ErrorHandler;
use PayMaya\API\Webhook as PayMayaWebhook;

class Webhook extends PayMayaWebhook
{
    use ErrorHandler;

    const CHECKOUT_DROPOUT = 'CHECKOUT_DROPOUT';

    public function __construct()
    {
        $this->apiManager = new CheckoutAPIManager();
    }

    public static function retrieve()
    {
        $apiManager = new CheckoutAPIManager();
        $response = $apiManager->retrieveWebhook();
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return [];
        }

        if (isset($responseArr['code']) || isset($responseArr['message'])) {
            return [];
        }

        $webhooks = [];
        foreach ($responseArr as $webhookInfo) {
            $webhook = new self();
            $webhook->id = $webhookInfo['id'];
            $webhook->name = $webhookInfo['name'];
            $webhook->callbackUrl = $webhookInfo['callbackUrl'];
            $webhooks[] = $webhook;
        }

        return $webhooks;
    }

    public function register()
    {
        $webhookInformation = json_decode(json_encode($this), true);
        $response = $this->apiManager->registerWebhook($webhookInformation);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = $responseArr['id'];

        return $responseArr;
    }

    public function update()
    {
        $webhookInformation = json_decode(json_encode($this), true);
        $response = $this->apiManager->updateWebhook($this->id, $webhookInformation);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = $responseArr['id'];
        $this->name = $responseArr['name'];
        $this->callbackUrl = $responseArr['callbackUrl'];

        return $responseArr;
    }

    public function delete()
    {
        $response = $this->apiManager->deleteWebhook($this->id);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->id = null;
        $this->name = null;
        $this->callbackUrl = null;

        return $responseArr;
    }
}
