<?php

require __DIR__.'/vendor/autoload.php';

use Aceraven777\PayMaya\PayMayaSDK;

PayMayaSDK::getInstance()->initCheckout(
    $_ENV['PUBLIC_API_KEY'],
    $_ENV['SECRET_API_KEY'],
    'SANDBOX');
