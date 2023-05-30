<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects;

class Transaction {
	/**
	 * Transaction Objects
	 *
	 * @var Objects\TransactionObject|Objects\RefundObject|Objects\VoidObject
	 */
	protected Objects\TransactionObject|Objects\RefundObject|Objects\VoidObject $obj;

	/**
	 * Builder Object
	 *
	 * @var Builder
	 */
	protected Library\Builder $builder;

	/**
	 * Load Transaction Type Object
	 *
	 * @param Objects\TransactionObject|Objects\RefundObject|Objects\VoidObject $obj
	 */
	public function __construct(Objects\TransactionObject|Objects\RefundObject|Objects\VoidObject $obj) {
		$this->obj = $obj;
		$this->builder = new Library\Builder("createTransactionRequest", $obj->getData());
	}

	/**
	 * Execute Responses
	 *
	 * @return Response|null
	 */
	public function execute(): ?Library\Response {
		$request = new Library\Request($this->builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Get XML
	 * 		For Debugging Purposes only.
	 *
	 * @return String
	 */
	public function getXML(): string {
		return $this->builder->getRequest()->asXML();
	}
}