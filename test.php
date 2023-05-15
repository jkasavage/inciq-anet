<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction as Transaction;

$arr = [
	'cardNumber' => 4111111111111111, //Required
	'expiration' => '0525', // Required
	'amount' => '60.00', //Required (Should include Shipping Cost)
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
		'lastName' => 'Name',
		'company' => 'Incubate IQ',
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
	'items' => [
		[
			'id' => '1',
			'name' => '2 Piece Dubbie',
			'description' => '2 Piece Dubbie, Duh!',
			'quantity' => 1,
			'price' => '10.00'
		],
		[
			'id' => '2',
			'name' => '3 Piece Duck',
            'description' => '3 Piece Duck, Duh!',
            'quantity' => 1,
            'price' => '10.00'
		]
	],
	'transactionType' => 'authCaptureTransaction', //Required
	'customerID' => '2001',
	'email' => 'developer@intranetiq.com',
	'save_card' => true
];

$customer = new Transaction\TransactionObject($arr);
$pay = new Transaction\Transaction($customer);

$test = $pay->pay();

print_r($test);