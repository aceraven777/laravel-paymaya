<?php

namespace Aceraven777\PayMaya\API;

use Aceraven777\PayMaya\Core\CheckoutAPIManager;

class Customization
{
	public $logoUrl;
	public $iconUrl;
	public $appleTouchIconUrl;
	public $customTitle;
	public $colorScheme;

	private $apiManager;

	public function __construct()
	{
		$this->apiManager = new CheckoutAPIManager();
	}

	public function set()
	{
		$customizationInformation = json_decode(json_encode($this), true);
		$response = $this->apiManager->setCustomization($customizationInformation);
		$responseArr = json_decode($response, true);

		$this->logoUrl = isset($responseArr["logoUrl"]) ? $responseArr["logoUrl"] : '';
		$this->iconUrl = isset($responseArr["iconUrl"]) ? $responseArr["iconUrl"] : '';
		$this->appleTouchIconUrl = isset($responseArr["appleTouchIconUrl"]) ? $responseArr["appleTouchIconUrl"] : '';
		$this->customTitle = isset($responseArr["customTitle"]) ? $responseArr["customTitle"] : '';
		$this->colorScheme = isset($responseArr["colorScheme"]) ? $responseArr["colorScheme"] : '';

		return $response;
	}

	public function get()
	{
		$response = $this->apiManager->getCustomization();
		$responseArr = json_decode($response, true);

		$this->logoUrl = isset($responseArr["logoUrl"]) ? $responseArr["logoUrl"] : '';
		$this->iconUrl = isset($responseArr["iconUrl"]) ? $responseArr["iconUrl"] : '';
		$this->appleTouchIconUrl = isset($responseArr["appleTouchIconUrl"]) ? $responseArr["appleTouchIconUrl"] : '';
		$this->customTitle = isset($responseArr["customTitle"]) ? $responseArr["customTitle"] : '';
		$this->colorScheme = isset($responseArr["colorScheme"]) ? $responseArr["colorScheme"] : '';
		
		return $response;
	}

	public function remove()
	{
		$response = $this->apiManager->removeCustomization();
		
		$this->logoUrl = null;
		$this->iconUrl = null;
		$this->appleTouchIconUrl = null;
		$this->customTitle = null;
		$this->colorScheme = null;

		return $response;
	}
}
