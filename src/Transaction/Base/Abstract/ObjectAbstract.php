<?php

namespace Incubateiq\Gateway\Transaction\Base\Abstract;

abstract class ObjectAbstract {
	/**
	 * Object Data
	 *
	 * @var array
	 */
	protected array $data;

	/**
	 * Dynamically Set Data
	 *
	 * @param array $data
	 */
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
	public function getData(): array {
		return $this->data;
	}
}