<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$arr = [
	"refId" => "re", // re - Refund Transaction
	"transactionRequest" => [
		"transactionType" => "refundTransaction",
		"amount" => "60.00",
		"payment" => [
			"creditCard" => [
				"cardNumber" => "1111",
				"expirationDate" => "XXXX"
			],
		],
		"refTransId" => "60219060666"
	]
];

$obj = new Library\Objects\RefundObject($arr);
$transaction = new Library\Transaction($obj);

$response = $transaction->execute();

print_r($response->getTransactionResponse());