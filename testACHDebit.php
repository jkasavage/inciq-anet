<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;

$arr = [
	"refId" => "de",
	"transactionRequest" => [
		"transactionType" => "authCaptureTransaction",
		"amount" => "50.00",
		"payment" => [
			"bankAccount" => [
				"accountType" => "checking", // checking or savings
				"routingNumber" => "026009593",
				"accountNumber" => "1234567891",
				"nameOnAccount" => "Bill Smith"
			]
		],
		"order" => [
			"invoiceNumber" => "inc123456",
			"description" => "Your Order"
		],
		"lineItems" => [
			[
				"itemId" => "1",
				"name" => "Pair of Bobo's",
				"description" => "Duh.",
				"quantity" => "1",
				"unitPrice" => "46.00"
			]
		],
		"tax" => [
			"amount" => "2.00",
			"name" => "State Tax",
			"description" => "Local Sales Tax"
		],
		"shipping" => [
			"amount" => "2.00",
			"name" => "Shipping",
			"description" => "Shipping Cost"
		],
		"billTo" => [
			"firstName" => "Bill",
			"lastName" => "Smith",
			"company" => "Incubate IQ",
			"address" => "2001 County Line Rd",
			"city" => "Warrington",
			"state" => "PA",
			"zip" => "18976",
			"country" => "US"
		],
		"shipTo" => [
			"firstName" => "Bill",
			"lastName" => "Smith",
			"company" => "Incubate IQ",
			"address" => "2001 County Line Rd",
			"city" => "Warrington",
			"state" => "PA",
			"zip" => "18976",
			"country" => "US"
		],
		"customerIP" => "192.168.0.1"
	]
];

$obj = new Objects\DebitAchObject($arr);
$ach = new Library\Ach($obj);

try {
	$response = $ach->debitACH();

	if($response) {
		print_r($response);
	} else {
		echo "Error! Transaction Failed!";
	}
} catch(Exception $e) {
	echo "Error: " . $e->getMessage();
}