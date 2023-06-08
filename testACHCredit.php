<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;

$arr = [
	"refId" => "cr",
	"transactionRequest" => [
		"transactionType" => "refundTransaction",
		"amount" => "50.00",
		"payment" => [
			"bankAccount" => [
				"accountType" => "checking", // checking or savings
				"routingNumber" => "026009593",
				"accountNumber" => "1234567891",
				"nameOnAccount" => "Bill Smith"
			]
		],
		"refTransId" => "60219943283",
		"order" => [
			"invoiceNumber" => "1234522",
			"description" => "Refund"
		]
	]
];

$obj = new Objects\CreditAchObject($arr);
$ach = new Library\Ach($obj);

// Must wait for Transaction to settle prior to Crediting Account

try {
	$response = $ach->creditACH();

	if($response) {
		print_r($response);
	} else {
		echo "Error! Could not process transaction!";
	}
} catch(Exception $e) {
	echo "Error: " . $e->getMessage();
}