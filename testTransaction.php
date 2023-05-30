<?php

require("vendor/autoload.php");

use Incubateiq\Gateway\Transaction\Base as Library;

$arr = [
	"refId" => "cc", // cc - Credit Card Transaction
	"transactionRequest" => [
		"transactionType" => "authCaptureTransaction",
		"amount" => 60.00,
		"payment" => [
			"creditCard" => [
				"cardNumber" => "4111111111111111",
				"expirationDate" => "2025-12",
				"cardCode" => "123"
			]
		],
		"order" => [
			"invoiceNumber" => "inq12345678",
			"description" => "Your Order"
		],
		"lineItems" => [
			[
				'itemId' => 1,
				'name' => "Test Transaction Item",
				'description' => "Test Item",
				'quantity' => 1,
				'unitPrice' => 56.00
			]
		],
		"tax" => [
			"amount" => 2.00,
			"name" => "Sales Tax",
			"description" => "Sales Tax"
		],
		"shipping" => [
			"amount" => 2.00,
			"name" => "Shipping Cost",
			"description" => "Shipping"
		],
		"billTo" => [
			"firstName" => "Test",
			"lastName" => "Name",
			"company" => "Incubate IQ",
			"address" => "2001 County Line Rd.",
			"city" => "Warrington",
			"state" => "PA",
			"zip" => "18976",
			"country" => "US"
		],
		"shipTo" => [
			"firstName" => "Test",
			"lastName" => "Name",
			"company" => "Incubate IQ",
			"address" => "2001 County Line Rd.",
			"city" => "Warrington",
			"state" => "PA",
			"zip" => "18976",
			"country" => "US"
		]
	]
];

$obj = new Library\Objects\TransactionObject($arr);
$trans = new Library\Transaction($obj);

echo "XML: " . $trans->getXML() . "\n\n";

$response =  $trans->execute();

echo "Response Code: " . $response->getResultCode() . "\n\n";
echo "Transaction ID: " . $response->getTransactionId() . "\n\n";
echo "Auth Code: " . $response->getAuthCode() . "\n\n";