<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;

$arr = [
	"profile" => [
		"merchantCustomerId" => "4002",
		"description" => "Bill Smith's Account",
		"email" => "billsmith@gmail.com",
		"paymentProfiles" => [
			"customerType" => "individual", // Individual or Business
			"payment" => [
				"creditCard" => [
					"cardNumber" => "4111111111111111",
					"expirationDate" => "2026-12"
				]
			]
		]
	]
];

$obj = new Objects\CustomerProfileObject($arr);
$customer = new Library\Customer($obj);

try {
	$response = $customer->createCustomer();

	if($response) {
		echo "Customer ID: " . $response;
	} else {
		echo "Error!";
	}
} catch(Exception $e) {
	echo $e->getMessage();
}
