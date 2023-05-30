<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

/**
 * Process Transaction Refund
 */
class RefundObject extends Abstract\ObjectAbstract {
	/**
	 * Refund Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"refId" => "", // re - Refund Transaction
		"transactionRequest" => [
			"transactionType" => "",
			"amount" => "",
			"payment" => [
				"creditCard" => [
					"cardNumber" => "",
					"expirationDate" => ""
				],
			],
			"refTransId" => ""
		]
	];
}