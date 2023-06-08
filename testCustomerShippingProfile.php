<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;

$arr = [
	"customerProfileId" => "912210660",
	"address" => [
		"firstName" => "Test",
		"lastName" => "Shipment",
		"company" => "Profile",
		"address" => "2001 County Line Rd",
		"city" => "Warrington",
		"state" => "PA",
		"zip" => "18976",
		"country" => "USA",
		"phoneNumber" => "1234567890",
		"faxNumber" => "1234567890"
	],
	"defaultShippingAddress" => true
];

$obj = new Objects\CustomerShippingObject($arr);
$customer = new Library\Customer($obj);

try {
	$response = $customer->createCustomerShippingProfile();

	if($response) {
		echo "Customer Shipping Profile: " . $response;
	} else {
		echo "Unknown Error.";
	}
} catch(Exception $e) {
	echo "Error: " . $e->getMessage();
}