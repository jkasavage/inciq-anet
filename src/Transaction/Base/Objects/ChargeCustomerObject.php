<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class ChargeCustomerObject extends Abstract\ObjectAbstract {
	/**
	 * Charge Customer Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"refId" => "",
		"transactionRequest" => [
			"transactionType" => "",
			"amount" => "",
			"profile" => [
				"customerProfileId" => "",
				"paymentProfile" => [
					"paymentProfileId" => "",
					"cardCode" => ""
				]
			]
		]
	];
}