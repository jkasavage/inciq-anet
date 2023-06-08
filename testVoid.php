<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$arr = [
	"refId" => "vo", // vo - Void Transaction
	"transactionRequest" => [
		"transactionType" => "voidTransaction",
		"refTransId" => "60219854756"
	]
];

$obj = new Library\Objects\VoidObject($arr);
$transaction = new Library\Transaction($obj);

$response = $transaction->execute();

print_r($response->getTransactionResponse());