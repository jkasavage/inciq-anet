<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

/**
 * Create Customer Profile
 */
class CustomerProfileObject extends Abstract\ObjectAbstract {
	/**
	 * Customer Profile Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"profile" => [
			"merchantCustomerId" => "",
			"description" => "",
			"email" => "",
			"paymentProfiles" => [
				"customerType" => "", // Individual or Business
				"payment" => [
					"creditCard" => [
						"cardNumber" => "",
						"expirationDate" => ""
					]
				]
			]
		]
	];
}