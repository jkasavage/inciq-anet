<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

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