<?php

namespace Incubateiq\Gateway\Transaction;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as Constants;

class Merchant {
	/**
	 * Credentials
	 *
	 * @var array|string[]
	 */
	private array $credentials = [
		'login_id' => '5TaH77qfMs',
		'transaction_key' => '757759HJp6s9nHt8'
	];

	private AnetAPI\MerchantAuthenticationType $merchant;

	public function __construct() {
		$merchant = new AnetAPI\MerchantAuthenticationType();
		$merchant->setName($this->credentials['login_id']);
		$merchant->setTransactionKey($this->credentials['transaction_key']);

		$this->merchant = $merchant;
	}

	/**
	 * Get Merchant
	 *
	 * @return AnetAPI\MerchantAuthenticationType
	 */
	public function getMerchant(): AnetAPI\MerchantAuthenticationType {
		return $this->merchant;
	}
}