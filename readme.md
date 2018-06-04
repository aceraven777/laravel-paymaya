# Integrate PayMaya payments in Laravel

[![Build Status](https://travis-ci.org/aceraven777/laravel-paymaya.svg?branch=master)](https://travis-ci.org/aceraven777/laravel-paymaya)

Integrated PayMaya SDK (https://github.com/PayMaya/PayMaya-PHP-SDK) and port it to Laravel.

## Installation

Run the following command to install:

```bash
composer require aceraven777/laravel-paymaya
```

Run the following command to publish `User` library file:

```bash
php artisan vendor:publish --provider "Aceraven777\PayMaya\PayMayaServiceProvider"
```

## Usage

When you run `php artisan vendor:publish` it will create file in `app/Libraries/PayMaya/User.php`, you may edit this file based on your needs.

Below is my sample controller if you want to test PayMaya, remember to edit your `.env` file and add environment variables for `PAYMAYA_PUBLIC_KEY` and `PAYMAYA_SECRET_KEY` for your API keys. Here's the link for sandbox API keys for PayMaya: https://developers.paymaya.com/blog/entry/api-test-merchants-and-test-cards-2

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Aceraven777\PayMaya\PayMayaSDK;
use Aceraven777\PayMaya\API\Webhook;
use Aceraven777\PayMaya\API\Checkout;
use Aceraven777\PayMaya\API\Customization;
use Aceraven777\PayMaya\Model\Checkout\Item;
use App\Libraries\PayMaya\User as PayMayaUser;
use Aceraven777\PayMaya\Model\Checkout\ItemAmount;
use Aceraven777\PayMaya\Model\Checkout\ItemAmountDetails;

class PayMayaTestController extends Controller
{
    public function setupPayMaya()
    {
        PayMayaSDK::getInstance()->initCheckout(
            env('PAYMAYA_PUBLIC_KEY'),
            env('PAYMAYA_SECRET_KEY'),
            (\App::environment('production') ? 'PRODUCTION' : 'SANDBOX')
        );

        $this->setShopCustomization();
        $this->setWebhooks();

        return redirect('/');
    }

    public function redirectToPayMaya()
    {
        $sample_item_name = 'Product 1';
        $sample_total_price = 1000.00;

        $sample_user_phone = '1234567';
        $sample_user_email = 'test@gmail.com';

        $sample_reference_number = '1234567890';

        // Item
        $itemAmountDetails = new ItemAmountDetails();
        $itemAmountDetails->tax = "0.00";
        $itemAmountDetails->subtotal = number_format($sample_total_price, 2, '.', '');
        $itemAmount = new ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = $itemAmountDetails->subtotal;
        $itemAmount->details = $itemAmountDetails;
        $item = new Item();
        $item->name = $sample_item_name;
        $item->amount = $itemAmount;
        $item->totalAmount = $itemAmount;

        // Checkout
        $itemCheckout = new Checkout();

        $user = new PayMayaUser();
        $user->contact->phone = $sample_user_phone;
        $user->contact->email = $sample_user_email;

        $itemCheckout->buyer = $user->buyerInfo();
        $itemCheckout->items = array($item);
        $itemCheckout->totalAmount = $itemAmount;
        $itemCheckout->requestReferenceNumber = $sample_reference_number;
        $itemCheckout->redirectUrl = array(
            "success" => url('returl-url/success'),
            "failure" => url('returl-url/failure'),
            "cancel" => url('returl-url/cancel'),
        );
        
        if ($itemCheckout->execute() === false) {
            $error = $itemCheckout::getError();
            return redirect()->back()->withErrors(['message' => $error['message']]);
        }

        if ($itemCheckout->retrieve() === false) {
            $error = $itemCheckout::getError();
            return redirect()->back()->withErrors(['message' => $error['message']]);
        }

        return redirect()->to($itemCheckout->url);
    }

    public function callback(Request $request)
    {
        $transaction_id = $request->get('id');
        if (! $transaction_id) {
            return ['status' => false, 'message' => 'Transaction Id Missing'];
        }
        
        $itemCheckout = new Checkout();
        $itemCheckout->id = $transaction_id;

        $response = $itemCheckout->retrieve();

        if ($response === false) {
            $error = $itemCheckout::getError();
            return redirect()->back()->withErrors(['message' => $error['message']]);
        }

        return $response;
    }

    private function setShopCustomization()
    {
        $shopCustomization = new Customization();
        $shopCustomization->get();

        $shopCustomization->logoUrl = asset('logo.jpg');
        $shopCustomization->iconUrl = asset('favicon.ico');
        $shopCustomization->appleTouchIconUrl = asset('favicon.ico');
        $shopCustomization->customTitle = 'PayMaya Payment Gateway';
        $shopCustomization->colorScheme = '#f3dc2a';

        $shopCustomization->set();
    }

    private function setWebhooks()
    {
        $this->clearWebhooks();

        $successWebhook = new Webhook();
        $successWebhook->name = Webhook::CHECKOUT_SUCCESS;
        $successWebhook->callbackUrl = url('callback/success');
        $successWebhook->register();

        $failureWebhook = new Webhook();
        $failureWebhook->name = Webhook::CHECKOUT_FAILURE;
        $failureWebhook->callbackUrl = url('callback/error');
        $failureWebhook->register();

        $dropoutWebhook = new Webhook();
        $dropoutWebhook->name = Webhook::CHECKOUT_DROPOUT;
        $dropoutWebhook->callbackUrl = url('callback/dropout');
        $dropoutWebhook->register();
    }

    private function clearWebhooks()
    {
        $webhooks = Webhook::retrieve();
        foreach ($webhooks as $webhook) {
            $webhook->delete();
        }
    }
}
```

## Donate

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q4XLBV46V3958)
