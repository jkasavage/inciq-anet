<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class DebitAchObject extends Abstract\ObjectAbstract {
	/**
	 * ACH Debit Object
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"refId" => "",
		"transactionRequest" => [
			"transactionType" => "",
			"amount" => "",
			"payment" => [
				"bankAccount" => [
					"accountType" => "", // Checking or Savings
					"routingNumber" => "",
					"accountNumber" => "",
					"nameOnAccount" => ""
				]
			],
			"order" => [
				"invoiceNumber" => "",
				"description" => ""
			],
			"lineItems" => [
				[
					"itemId" => "",
					"name" => "",
					"description" => "",
					"quantity" => "",
					"unitPrice" => ""
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
			"poNumber" => "", // B2B
			"billTo" => [
				"firstName" => "",
				"lastName" => "",
				"company" => "",
				"address" => "",
				"city" => "",
				"state" => "",
				"zip" => "",
				"country" => ""
			],
			"shipTo" => [
				"firstName" => "",
				"lastName" => "",
				"company" => "",
				"address" => "",
				"city" => "",
				"state" => "",
				"zip" => "",
				"country" => ""
			],
			"customerIP" => ""
		]
	];
}