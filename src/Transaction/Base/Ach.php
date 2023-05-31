<?php

namespace Incubateiq\Gateway\Transaction\Base;

use ErrorException;
use Incubateiq\Gateway\Transaction\Base\Objects;
use Incubateiq\Gateway\Transaction\Base as Library;

class Ach {
	/**
	 * ACH Object
	 *
	 * @var Objects\DebitAchObject|Objects\CreditAchObject
	 */
	protected Objects\DebitAchObject|Objects\CreditAchObject $ach;

	public function __construct(Objects\DebitAchObject|Objects\CreditAchObject $ach) {
		$this->ach = $ach;
	}

	/**
	 * ACH Debit
	 *
	 * @return array|null
	 *
	 * @throws ErrorException
	 */
	public function debitACH(): ?array {
		if(!$this->ach instanceof Objects\DebitAchObject) {
			throw new ErrorException("You must use the Objects\DebitAchObject object to process a Debit Transaction.");
		}

		$builder = new Library\Builder("createTransactionRequest", $this->ach->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionResponse();
		} else {
			return null;
		}
	}

	/**
	 * ACH Credit
	 *
	 * @return array|null
	 *
	 * @throws ErrorException
	 */
	public function creditACH(): ?array {
		if(!$this->ach instanceof Objects\CreditAchObject) {
			throw new ErrorException("You must use the Objects\DebitAchObject object to process a Debit Transaction.");
		}

		$builder = new Library\Builder("createTransactionRequest", $this->ach->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionResponse();
		} else {
			return null;
		}
	}
}