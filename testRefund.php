<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction as Library;

$arr = [
	'transactionId'=>'60217543654',
	'amount' => '60.00'
];

$object = new Library\RefundObject($arr);
$refund = new Library\TransactionRefund($object);

$proc = $refund->process();

print_r($proc);