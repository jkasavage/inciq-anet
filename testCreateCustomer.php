<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$data = [
	"customerId" => "123459",
	"customerType" => "individual", // Individual or Business etc.
	"description" => "Billy Smithy",
	"email" => "joe@incubateiq.com",
	"paymentProfiles" => [
		"creditCard" => [
			"cardNumber" => "4111 1111 1111 1111",
			"expiration" => "2028-06",
			"cardCode" => "168"
		]
	],
	"billTo"=> [
		"firstName" => "Billy",
		"lastName" => "Smithy",
		"company" => "Smith Co",
		"address" => "2001 County Line Rd.",
		"city" => "Warrington",
		"state" => "PA",
		"zip" => "18976",
		"country" => "US",
		"phoneNumber" => "2678924093",
		"faxNumber" => "2678924093"
	],
	"shipTo"=> null
];

$trans = new Library\Customer();
$obj = new Library\CreateCustomerObject($data);

//$customer = $trans->createCustomer($obj);
//$customerId = $customer->getCustomerProfileId();

//$paymentProfile = $trans->createCustomerPaymentProfile("911985341", $obj);
//echo $paymentProfile->getCustomerPaymentProfileId();

$shippingProfile = $trans->createCustomerShippingProfile("911985341", $obj);
echo $shippingProfile->getCustomerShippingProfileId();