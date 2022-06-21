<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Core\CheckoutAPIManager;
use Aceraven777\PayMaya\Traits\ErrorHandler;
use PayMaya\API\Customization as PayMayaCustomization;

class Customization extends PayMayaCustomization
{
    use ErrorHandler;

    public function __construct()
    {
        $this->apiManager = new CheckoutAPIManager();
    }

    public function set()
    {
        $customizationInformation = json_decode(json_encode($this), true);
        $response = $this->apiManager->setCustomization($customizationInformation);
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->logoUrl = isset($responseArr['logoUrl']) ? $responseArr['logoUrl'] : '';
        $this->iconUrl = isset($responseArr['iconUrl']) ? $responseArr['iconUrl'] : '';
        $this->appleTouchIconUrl = isset($responseArr['appleTouchIconUrl']) ? $responseArr['appleTouchIconUrl'] : '';
        $this->customTitle = isset($responseArr['customTitle']) ? $responseArr['customTitle'] : '';
        $this->colorScheme = isset($responseArr['colorScheme']) ? $responseArr['colorScheme'] : '';

        return $responseArr;
    }

    public function get()
    {
        $response = $this->apiManager->getCustomization();
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->logoUrl = isset($responseArr['logoUrl']) ? $responseArr['logoUrl'] : '';
        $this->iconUrl = isset($responseArr['iconUrl']) ? $responseArr['iconUrl'] : '';
        $this->appleTouchIconUrl = isset($responseArr['appleTouchIconUrl']) ? $responseArr['appleTouchIconUrl'] : '';
        $this->customTitle = isset($responseArr['customTitle']) ? $responseArr['customTitle'] : '';
        $this->colorScheme = isset($responseArr['colorScheme']) ? $responseArr['colorScheme'] : '';

        return $responseArr;
    }

    public function remove()
    {
        $response = $this->apiManager->removeCustomization();
        $responseArr = json_decode($response, true);

        if (! self::isResponseValid($responseArr)) {
            return false;
        }

        $this->logoUrl = null;
        $this->iconUrl = null;
        $this->appleTouchIconUrl = null;
        $this->customTitle = null;
        $this->colorScheme = null;

        return $responseArr;
    }
}
