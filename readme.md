# Integrate PayMaya payments in Laravel

Integrated PayMaya SDK (https://github.com/PayMaya/PayMaya-PHP-SDK) and port it to Laravel.

## Installation

Run the following command to install:

```bash
composer require "aceraven777/paymaya-sdk":"^1.0.0"
```

Run the following command to publish `User` library file:

```bash
php artisan vendor:publish --provider "Aceraven777\PayMaya\PayMayaServiceProvider"
```

## Usage

When you run `php artisan vendor:publish` it will create file in `app/Libraries/PayMaya/User.php`, you may edit this file based on your needs.

This is my sample controller if you want to test PayMaya:

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
    public function redirectToPayMaya()
    {
        $this->setupPayMaya();

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
        $itemCheckout->execute();
        $itemCheckout->retrieve();

        return redirect()->to($itemCheckout->url);
    }

    public function callback(Request $request)
    {
        $transaction_id = $request->get('id');
        if (! $transaction_id) {
            return ['status' => false, 'message' => 'Transaction Id Missing'];
        }

        $this->setupPayMaya();
        
        $itemCheckout = new Checkout();
        $itemCheckout->id = $transaction_id;

        $response = $itemCheckout->retrieve();

        if (! $response) {
            return ['status' => false, 'message' => 'Invalid transaction'];
        }

        return json_decode($response, true);
    }

    private function setupPayMaya()
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
        $webhooks = Webhook::retrieve();
        foreach ($webhooks as $webhook) {
            $webhook->delete();
        }

        $successWebhook = new Webhook();
        $successWebhook->name = Webhook::CHECKOUT_SUCCESS;
        $successWebhook->callbackUrl = url('callback/success');
        $successWebhook->register();

        $failureWebhook = new Webhook();
        $failureWebhook->name = Webhook::CHECKOUT_FAILURE;
        $failureWebhook->callbackUrl = url('callback/error');
        $failureWebhook->register();
    }
}
```

## Donate

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q4XLBV46V3958)