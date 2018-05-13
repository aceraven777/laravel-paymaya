<?php

require __DIR__ . '/vendor/autoload.php';

use Aceraven777\PayMaya\PayMayaSDK;
PayMayaSDK::getInstance()->initCheckout(
	"pk-nRO7clSfJrojuRmShqRbihKPLdGeCnb9wiIWF8meJE9", 
	"sk-jZK0i8yZ30ph8xQSWlNsF9AMWfGOd3BaxJjQ2CDCCZb", 
	"SANDBOX");