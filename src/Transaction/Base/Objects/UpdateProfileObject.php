<?php

namespace Incubateiq\Gateway\Transaction\Base\Objects;

use Incubateiq\Gateway\Transaction\Base\Abstract;

class UpdateProfileObject extends Abstract\ObjectAbstract {
	protected array $data = [
		"profile" => [
			"merchantCustomerId" => "",
			"description" => "",
			"email" => "",
			"customerProfileId" => ""
		]
	];
}