<?php

namespace Incubateiq\Gateway\Transaction\Base;

class PaymentProfileObject {
	/**
	 * Payment Profile Object Data
	 *
	 * @var array
	 */
	protected array $data = [
		"customerProfileId" => "",
		"paymentProfile" => [
			"billTo" => [
				"firstName"	=> "",
				"lastName"	=> "",
				"company"	=> "",
				"address" 	=> "",
				"city"		=> "",
				"state" 	=> "",
				"zip"		=> "",
				"country" 	=> "",
				"phoneNumber" => "",
				"faxNumber" => ""
			],
			"payment" => [
				"creditCard" => [
					"cardNumber" => "",
					"expirationDate" => ""
				]
			],
			"defaultPaymentProfile" => false,
			"customerPaymentProfileId" => ""
		]
	];

	public function __construct(array $data) {
		foreach($data as $key=>$value) {
			$this->__set($key, $value);
		}
	}

	/**
	 * Dynamically Set Values
	 *
	 * @param string $property
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function __set(string $property, $value) {
		$this->data[$property] = $value;
	}

	/**
	 * Dynamically Get Methods
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get(string $property) {
		return $this->data[$property] ?? null;
	}

	/**
	 * Get Transaction Data
	 * 		Debugging
	 *
	 * @return array
	 */
	public function getTransactionData(): array {
		return $this->data;
	}
}