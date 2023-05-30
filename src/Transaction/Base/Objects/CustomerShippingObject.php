<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class CustomerShippingObject extends Abstract\ObjectAbstract {
	/**
	 * Customer Shipping Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"customerProfileId" => "",
		"address" => [
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
		"defaultShippingAddress" => false
	];
}