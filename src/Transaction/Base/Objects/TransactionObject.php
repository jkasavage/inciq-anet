<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

/**
 * Process Customer Transaction
 */
class TransactionObject extends Abstract\ObjectAbstract {
	/**
	 * Transaction Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"refId" => "", // cc - Credit Card Transaction
		"transactionRequest" => [
			"transactionType" => "",
			"amount" => "",
			"payment" => [
				"creditCard" => [
					"cardNumber" => "",
					"expirationDate" => "",
					"cardCode" => ""
				]
			],
			"order" => [
				"invoiceNumber" => "",
				"description" => ""
			],
			"lineItems" => [
				[
					'id' => "",
					'name' => "",
					'description' => "",
					'quantity' => "", // Integer or String
					'price' => ""
				]
			],
			"tax" => [
				"amount" => "",
				"name" => "",
				"description" => ""
			],
			"shipping" => [
				"amount" => "",
				"name" => "",
				"description" => ""
			],
			"poNumber" => "", // When Applicable
			"customer" => [
				"id" => "",
			],
			"billTo" => [
				'firstName' => "",
				'lastName' => "",
				'company' => "",
				'address' => "",
				'city' => "",
				'state' => "",
				'zip' => "",
				'country' => ""
			],
			"shipTo" => [
				'firstName' => "",
				'lastName' => "",
				'company' => "",
				'address' => "",
				'city' => "",
				'state' => "",
				'zip' => "",
				'country' => ""
			],
			"customerIP" => ""
		]
	];
}