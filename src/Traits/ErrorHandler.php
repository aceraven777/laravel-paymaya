<?php

namespace Aceraven777\PayMaya\Traits;

trait ErrorHandler
{
    private static $error;

    public static function getError()
    {
        return self::$error;
    }

    private static function isResponseValid($responseArr)
    {
        if (isset($responseArr['error'])) {
            self::$error = $responseArr['error'];

            return false;
        } elseif (isset($responseArr['message']) && in_array($responseArr['message'], ['Invalid authentication credentials', 'Invalid endpoint'])) {
            self::$error['code'] = null;
            self::$error['message'] = $responseArr['message'];

            return false;
        }

        return true;
    }
}
