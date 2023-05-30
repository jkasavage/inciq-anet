<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base as Library;

class Reports {
	public function __construct() {}

	/**
	 * Get Settled Batch List
	 *
	 * @param \DateTime $first
	 * @param \DateTime $last
	 *
	 * @return array|null
	 */
	public function getSettledBatchList(\DateTime $first, \DateTime $last): ?array {
		$arr = [
			"firstSettlementDate" => $first->format('c'),
			"lastSettlementDate" => $last->format('c')
		];

		$builder = new Library\Builder("getSettledBatchListRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getBatchList();
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction List
	 *
	 * @param string $batchId
	 *
	 * @param int $page
	 * @return array|null
	 */
	public function getTransactionList(string $batchId, int $page=1): ?array {
		$arr = [
			"batchId" => $batchId,
			"sorting" => [
				"orderBy" => "submitTimeUTC",
				"orderDescending" => true
			],
			"paging" => [
				"limit" => 100,
				"offset" => $page
			]
		];

		$builder = new Library\Builder("getTransactionListRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionList();
		} else {
			return null;
		}
	}

	/**
	 * Get Unsettled Transaction List
	 *
	 * @param int $page
	 *
	 * @return array|null
	 */
	public function getUnsettledTransactionList(int $page=1): ?array {
		$arr = [
			"sorting" => [
				"orderBy" => "submitTimeUTC",
				"orderDescending" => true
			],
			"paging" => [
				"limit" => 100,
				"offset" => $page
			]
		];

		$builder = new Library\Builder("getUnsettledTransactionListRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionList();
		} else {
			return null;
		}
	}

	/**
	 * Get Customer Transaction List
	 *
	 * @param string $profile
	 * @param string $paymentProfile
	 * @param int $page
	 *
	 * @return array|null
	 */
	public function getCustomerTransactionList(string $profile, string $paymentProfile, int $page=1): ?array {
		$arr = [
			"customerProfileId" => $profile,
			"customerPaymentProfileId" => $paymentProfile,
			"sorting" => [
				"orderBy" => "submitTimeUTC",
				"orderDescending" => true
			],
			"paging" => [
				"limit" => 100,
				"offset" => $page
			]
		];

		$builder = new Library\Builder("getTransactionListForCustomerRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionList();
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction Details
	 *
	 * @param string $transId
	 *
	 * @return array|null
	 */
	public function getTransactionDetails(string $transId): ?array {
		$arr = [
			"transId" => $transId
		];

		$builder = new Library\Builder("getTransactionDetailsRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionDetails();
		} else {
			return null;
		}
	}

	/**
	 * Get Batch Details
	 *
	 * @param string $batchId
	 *
	 * @return array|null
	 */
	public function getBatchStats(string $batchId): ?array {
		$arr = [
			"batchId" => $batchId
		];

		$builder = new Library\Builder("getBatchStatisticsRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			$response->getBatchDetails();
		} else {
			return null;
		}
	}

	/**
	 * Get Merchant Details
	 *
	 * @return array|null
	 */
	public function getMerchantDetails(): ?array {
		$builder = new Library\Builder("getMerchantDetailsRequest");
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getMerchantDetails();
		} else {
			return null;
		}
	}
}