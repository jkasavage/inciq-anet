<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class CreditAchObject extends Abstract\ObjectAbstract {
	/**
	 * ACH Credit Object
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
					"accountType" => "",
					"routingNumber" => "",
					"accountNumber" => "",
					"nameOnAccount" => ""
				]
			],
			"refTransId" => "",
			"order" => [
				"invoiceNumber" => "",
				"description" => ""
			]
		]
	];
}