<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;

$arr = [
	"customerProfileId" => "912210660",
	"paymentProfile" => [
		"billTo" => [
			"firstName" => "Test",
			"lastName" => "Payment",
			"company" => "Incubate IQ",
			"address" => "2001 County Line Rd",
			"city" => "Warrington",
			"state" => "PA",
			"zip" => "18976",
			"country" => "USA",
			"phoneNumber" => "6109388421",
			"faxNumber" => "1234567890"
		],
		"payment" => [
			"creditCard" => [
				"cardNumber" => "4111111111111111",
				"expirationDate" => "2029-12"
			]
		],
		"defaultPaymentProfile" => true
	]
];

$obj = new Objects\CustomerPaymentObject($arr);
$trans = new Library\Customer($obj);

try {
	$response = $trans->createCustomerPaymentProfile();

	echo "Customer Payment Profile: " . $response;
} catch(Exception $e) {
	echo $e->getMessage();
}