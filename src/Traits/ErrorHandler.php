<?php

namespace Aceraven777\PayMaya\Traits;

trait ErrorHandler
{
    private static $error;

    public static function getError()
    {
        return self::$error;
    }

    private static function isResponseValid($responseArr, $isEmptyValid = false)
    {
        if (! $responseArr && $isEmptyValid) {
            return true;
        } elseif (! $responseArr) {
            self::$error['code'] = null;
            self::$error['message'] = 'API response empty';

            return false;
        } elseif (isset($responseArr['error'])) {
            self::$error['code'] = $responseArr['error'];

            return false;
        } elseif (isset($responseArr['message']) && count($responseArr) === 1) {
            self::$error['code'] = null;
            self::$error['message'] = $responseArr['message'];
            
            return false;
        }

        return true;
    }
}
