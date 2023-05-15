<?php

namespace Incubateiq\Gateway\Transaction;

use Incubateiq\Gateway\Transaction as Library;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as Constants;

class TransactionRefund {
	/**
	 * Merchant Authentication
	 *
	 * @var AnetAPI\MerchantAuthenticationType
	 */
	protected AnetAPI\MerchantAuthenticationType $merchant;

	/**
	 * Refund Object
	 *
	 * @var Library\RefundObject
	 */
	protected Library\RefundObject $object;

	/**
	 * Refund Reference ID
	 *
	 * @var string
	 */
	private string $refId;

	public function __construct(Library\RefundObject $object) {
		$this->object = $object;

		$merchant = new Library\Merchant();
		$this->merchant = $merchant->getMerchant();
	}

	/**
	 * Process Refund
	 *
	 * @return array
	 */
	public function process(): array {
		$transaction = new AnetAPI\TransactionRequestType();
		$transaction->setTransactionType('refundTransaction');
		$transaction->setRefTransId($this->object->__get('transactionId'));
		$transaction->setAmount($this->object->__get('amount'));

		// Change for Different Currency
		$transaction->setCurrencyCode('USD');

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchant);

		$this->setRefId();
		$request->setRefId($this->refId);
		$request->setTransactionRequest($transaction);

		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse(Constants\ANetEnvironment::SANDBOX);

		$return = [];

		if($response != null && $response->getMessages()->getResultCode() == 'Ok') {
			$return['refund'] = true;
			$return['ref'] = $this->refId;
		} else {
			$return['error'] = $response->getMessages()->getMessage()[0]->getText();
			$return['errorCode'] = $response->getMessages()->getMessage()[0]->getCode();
		}

		return $return;
	}

	/**
	 * Set Reference ID
	 *
	 * @return void
	 */
	private function setRefId(): void {
		$this->refId = 'refund' . time();
	}
}