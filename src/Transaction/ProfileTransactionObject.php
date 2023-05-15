<?php

namespace Incubateiq\Gateway\Transaction;

class ProfileTransactionObject {
	/**
	 * Profile Transaction Data
	 *
	 * @var array
	 */
	private array $data = [
		'customerId' => '',
		'profileId' => '',
		'paymentProfileId' => '',
		'shipping_cost' => [],
		'tax' => [],
		'items' => [],
		'shipping' => [],
		'amount' => '',
		'transactionType' => ''
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
		return $this->data[$property];
	}

	/**
	 * Get Transaction Data
	 *
	 * @return array
	 */
	public function getTransactionData(): array {
		return $this->data;
	}
}