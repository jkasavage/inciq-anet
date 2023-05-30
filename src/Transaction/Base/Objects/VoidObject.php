<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

/**
 * Process Customer Void
 */
class VoidObject extends Abstract\ObjectAbstract {
	/**
	 * Void Data
	 * 		For Reference only, will be overwritten on instantiation.
	 *
	 * @var array
	 */
	protected array $data = [
		"refId" => "", // vo - Void Transaction
		"transactionRequest" => [
			"transactionType" => "",
			"refTransId" => ""
		]
	];
}