<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction as Library;

$arr = [
	'customerId' => '2001',
	'profileId' => '911759913',
	'paymentProfileId' => '911114145',
	'shipping_cost' => [
		'amount' => '2.00',
		'name' => 'Shipping Cost',
		'description' => 'Shipping Cost'
	],
	'tax' => [
		'amount' => '2.00',
		'name' => 'Sales Tax',
		'description' => 'State Sales Tax'
	],
	'items' => [
		[
			'id' => '1',
			'name' => '1 Piece Dubbie',
			'description' => '1 Piece Dubbie, Duh!',
			'quantity' => 1,
			'price' => '18.00'
		],
		[
			'id' => '2',
			'name' => '4 Piece Duck',
			'description' => '4 Piece Duck, Duh!',
			'quantity' => 1,
			'price' => '18.00'
		]
	],
	'shipping' => [
		'firstName' => 'Tim',
		'lastName' => 'Talom',
		'company' => 'Incubate IQ',
		'address' => '2001 County Line Rd.',
		'city' => 'Warrington',
		'state' => 'PA',
		'zip' => '18976',
		'country' => 'USA',
		'email'=> 'joe@incubateiq.com'
	],
	'amount' => '50.00',
	'transactionType' => 'authCaptureTransaction'
];

$object = new Library\ProfileTransactionObject($arr);
$proc = new Library\ProfileTransaction($object);

$check = $proc->payByProfile();

print_r($check);