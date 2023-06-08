<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$arr = [
	"refId" => "cc", // cc - Credit Card Transaction
	"transactionRequest" => [
		"transactionType" => "authCaptureTransaction",
		"amount" => "50.00",
		"payment" => [
			"creditCard" => [
				"cardNumber" => "4111111111111111",
				"expirationDate" => "2026-12",
				"cardCode" => "168"
			]
		],
		"order" => [
			"invoiceNumber" => "123456",
			"description" => "Your Order"
		],
		"lineItems" => [
			[
				'id' => "1",
				'name' => "Some Item",
				'description' => "Item",
				'quantity' => "1", // Integer or String
				'price' => "46.00"
			]
		],
		"tax" => [
			"amount" => "2.00",
			"name" => "Sales Tax",
			"description" => "Tax"
		],
		"shipping" => [
			"amount" => "2.00",
			"name" => "Shipping Cost",
			"description" => "Shipping"
		],
		"poNumber" => "123456", // When Applicable
		"customer" => [
			"id" => "2001",
		],
		"billTo" => [
			'firstName' => "Test",
			'lastName' => "Name",
			'company' => "Here",
			'address' => "2001 County Line Road",
			'city' => "Warrington",
			'state' => "PA",
			'zip' => "18976",
			'country' => "US"
		],
		"shipTo" => [
			'firstName' => "Test",
			'lastName' => "Name",
			'company' => "Here",
			'address' => "2001 County Line Road",
			'city' => "Warrington",
			'state' => "PA",
			'zip' => "18976",
			'country' => "US"
		],
		"customerIP" => "192.168.0.1"
	]
];

$obj = new Library\Objects\TransactionObject($arr);
$trans = new Library\Transaction($obj);

$response = $trans->execute();

if($response->getResultCode() == "Ok") {
	echo "Success\n\n";
} else {
	echo "Error\n\n";
}