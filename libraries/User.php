<?php

namespace App\Libraries\PayMaya;

use Aceraven777\PayMaya\Model\Checkout\Buyer;
use Aceraven777\PayMaya\Model\Checkout\Address;
use Aceraven777\PayMaya\Model\Checkout\Contact;

class User
{
    public $firstName;
    public $middleName;
    public $lastName;
    public $contact;
    private $shippingAddress;
    private $billingAddress;

    public function __construct()
    {
        $this->firstName = '';
        $this->middleName = '';
        $this->lastName = '';

        // Contact
        $this->contact = new Contact();
        $this->contact->phone = '';
        $this->contact->email = '';

        // Address
        $address = new Address();
        $address->line1 = '';
        $address->line2 = '';
        $address->city = '';
        $address->state = '';
        $address->zipCode = '';
        $address->countryCode = 'PH';
        $this->shippingAddress = $address;
        $this->billingAddress = $address;
    }

    public function buyerInfo()
    {
        $buyer = new Buyer();
        $buyer->firstName = $this->firstName;
        $buyer->middleName = $this->middleName;
        $buyer->lastName = $this->lastName;
        $buyer->contact = $this->contact;
        $buyer->shippingAddress = $this->shippingAddress;
        $buyer->billingAddress = $this->billingAddress;

        return $buyer;
    }
}
