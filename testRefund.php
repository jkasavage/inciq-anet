<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$arr = [
	'cardNumber' => "4111111111111111", //Required
	'expiration' => '2026-05', // Required YYYY-MM
	'cvv' => '168', // Required
	'amount' => '80.00', //Required (Should include Shipping Cost)
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
	'invoiceId' => null,
	'description' => null,
	'billing' => [
		'firstName' => 'New',
		'lastName' => 'Wrapper',
		'company' => '',
		'address' => '2001 County Line Rd.',
		'city' => 'Warrington',
		'state' => 'PA',
		'zip' => '18976',
		'country' => 'USA',
		'phone' => '2672818200',
		'fax' => '2672818200',
		'email'=> 'joe@incubateiq.com'
	],
	'shipping' => [
		'firstName' => 'New',
		'lastName' => 'Wrapper',
		'company' => '',
		'address' => '2001 County Line Rd.',
		'city' => 'Warrington',
		'state' => 'PA',
		'zip' => '18976',
		'country' => 'USA',
		'email'=> 'joe@incubateiq.com'
	],
	'items' => [
		[
			'id' => '1',
			'name' => '2 Piece Dubbie',
			'description' => '2 Piece Dubbie, Duh!',
			'quantity' => 1,
			'price' => '38.00'
		],
		[
			'id' => '2',
			'name' => '3 Piece Duck',
			'description' => '3 Piece Duck, Duh!',
			'quantity' => 1,
			'price' => '38.00'
		]
	],
	'transactionType' => 'authCaptureTransaction', //Required
	'customerId' => '2009',
	'email' => time() . '@intranetiq.com',
	'save_card' => true,
	'transactionId' => '60218439743'
];

$obj = new Library\Objects\TransactionObject($arr);
$transaction = new Library\Transaction($obj);

$response = $transaction->execute();

echo $response->getResponse();