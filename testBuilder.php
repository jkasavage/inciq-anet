<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

// Testing Create Customer Profile
/*$arr = [
	"profile" => [
		"merchantCustomerId" => "2001",
		"description" => "Bill Smith",
		"email" => "bill@smith.com",
		"paymentProfile" => [
			"customerType" => "individual",
			"payment" => [
				"creditCard" => [
					"cardNumber" => "4111111111111111",
					"expirationDate" => "2025-12"
				]
			]
		]
	]
];

$builder = new Library\Builder("createCustomerProfileRequest", $arr);*/

// Testing Create Transaction Request
$arr = [
	"refId" => "cc",
	"transactionRequest" => [
		"transactionType" => "authCaptureTransaction",
		"amount" => "50.00",
		"payment" => [
			"creditCard" => [
				"cardNumber" => "4111111111111111",
				"expirationDate" => "2025-12",
				"cardCode" => "123"
			]
		],
		"order" => [
			"invoiceNumber" => "123456",
			"description" => "Your Order"
		],
		"lineItems" => [
			[
				'itemId' => '1',
				'name' => '2 Piece Dubbie',
				'description' => '2 Piece Dubbie, Duh!',
				'quantity' => 1,
				'unitPrice' => '38.00'
			],
			[
				'id' => '2',
				'name' => '3 Piece Duck',
				'description' => '3 Piece Duck, Duh!',
				'quantity' => 1,
				'price' => '38.00'
			]
		],
		"tax" => [
			"amount" => "2.00",
			"name" => "Sales Tax",
			"description" => "Sales Tax"
		],
		"duty" => [
			"amount" => "2.00",
			"name" => "Duty Tax",
			"description" => "Duty Tax"
		],
		"shipping" => [
			"amount" => "2.00",
			"name" => "Shipping Cost",
			"description" => "Shipping Cost"
		],
		"poNumber" => "123456", // When Applicable
		"customer" => [
			"id" => "2001"
		],
		"billTo" => [
			'firstName' => 'Builder',
			'lastName' => 'Test',
			'company' => 'Some Company Name',
			'address' => '2001 County Line Rd.',
			'city' => 'Warrington',
			'state' => 'PA',
			'zip' => '18976',
			'country' => 'USA'
		],
		"shipTo" => [
			'firstName' => 'Builder',
			'lastName' => 'Test',
			'company' => 'Some Company Name',
			'address' => '2001 County Line Rd.',
			'city' => 'Warrington',
			'state' => 'PA',
			'zip' => '18976',
			'country' => 'USA'
		],
		"customerIP" => "192.168.0.1"
	]
];

$object = new Library\Objects\TransactionObject($arr);

$builder = new Library\Builder("createTransactionRequest", $arr);

// Check XML
echo $builder->debug();

// Get Reference ID for transaction if needed
// Will only be used for Charge, Void, and Refund
//echo $builder->getRefId();