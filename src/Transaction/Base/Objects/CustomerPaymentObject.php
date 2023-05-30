<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class CustomerPaymentObject extends Abstract\ObjectAbstract {
	/**
	 * Customer Payment Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"customerProfileId" => "",
		"paymentProfile" => [
			"billTo" => [
				"firstName" => "",
				"lastName" => "",
				"company" => "",
				"address" => "",
				"city" => "",
				"state" => "",
				"zip" => "",
				"country" => "",
				"phoneNumber" => "",
				"faxNumber" => ""
			],
			"payment" => [
				"creditCard" => [
					"cardNumber" => "",
					"expirationDate" => ""
				]
			],
			"defaultPaymentProfile" => false
		]
	];
}