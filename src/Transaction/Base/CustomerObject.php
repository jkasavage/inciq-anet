<?php

namespace Incubateiq\Gateway\Transaction\Base;

class CustomerObject {
	/**
	 * List of Fields for Customer Object
	 * 		May change based on Response but should follow these guidelines when
	 * 		utilizing this library
	 *
	 * @var array|array[]
	 */
	protected array $data = [
		"messages" => [
			"resultCode" => "",
			"message" => [
				"code" => "",
				"text" => ""
			]
		],
		"profile" => [
			"merchantCustomerId" => "",
			"description" => "",
			"email" => "",
			"customerProfileId" => "",
			"paymentProfiles" => [ // May be Multiple depending on the Customer
				"customerType" => "",
				"billTo" => [
					"firstName" => "",
					"lastName" => "",
					"address" => "",
					"city" => "",
					"state" => "",
					"zip" => "",
					"country" => "",
					"phoneNumber" => "",
					"faxNumber" => ""
				],
				"customerPaymentProfileId" => "",
				"payment" => [
					"creditCard" => [
						"cardNumber" => "",
						"expirationDate" => "",
						"cardType" => "",
						"issuerNumber" => ""
					]
				]
			],
			"shipToList" => [ // May be Multiple depending on the Customer
				"firstName" => "",
				"lastName" => "",
				"company" => "",
				"address" => "",
				"city" => "",
				"state" => "",
				"zip" => "",
				"country" => "",
				"phoneNumber" => "",
				"faxNumber" => "",
				"customerAddressId" => ""
			],
			"profileType" => ""
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