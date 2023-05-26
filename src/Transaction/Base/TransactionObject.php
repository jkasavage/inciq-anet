<?php

namespace Incubateiq\Gateway\Transaction\Base;

class TransactionObject {
	/**
	 * Transaction Data
	 * 		Object Reference
	 *
	 * @var array
	 */
	private array $data = [
		'cardNumber' => null, //Required
		'expiration' => null, // Required YYYY-MM
		'cvv' => null, // Required
		'amount' => null, //Required (Should Include Shipping Cost),
		'shipping_cost' => [],
		'tax' => [],
		'invoiceId' => null,
		'description' => null,
		'billing' => [
			'firstName' => null,
			'lastName' => null,
			'company' => null,
			'address' => null,
			'city' => null,
			'state' => null,
			'zip' => null,
			'country' => null,
			'phone' => null,
			'fax' => null,
			'email' => null
		],
		'shipping' => [
			'firstName' => null,
			'lastName' => null,
			'company' => null,
			'address' => null,
			'city' => null,
			'state' => null,
			'zip' => null,
			'country' => null
		],
		'items' => [],
		'transactionType' => null,
		'customerID' => null,
		'email' => null,
		'transactionId' => null,
		'save_card' => false
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