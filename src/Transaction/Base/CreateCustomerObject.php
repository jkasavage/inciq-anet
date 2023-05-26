<?php

namespace Incubateiq\Gateway\Transaction\Base;

class CreateCustomerObject {
	/**
	 * List of Fields for Creating a Customer
	 * 		Will remain static unless Authorize.net API changes
	 *
	 * @var array|array[]
	 */
	protected array $data = [
		"customerId" => "",
		"customerType" => "", // Individual or Business etc.
		"description" => "",
		"email" => "",
		"default" => true, // Set To Default Payment Method
		"paymentProfiles" => [
			"creditCard" => [
				"cardNumber" => "",
				"expiration" => "", // YYYY-MM
				"cardCode" => ""
			]
		],
		"billTo"=> [
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
		"shipTo"=> [ // If not different then billTo keep this as null instead of array
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