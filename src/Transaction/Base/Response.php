<?php

namespace Incubateiq\Gateway\Transaction\Base;

use SimpleXMLElement as XML;

class Response {
	/**
	 * XML Response
	 *
	 * @var XML
	 */
	protected XML $xml;

	public function __construct(XML $xml) {
		$this->xml = $xml;
	}

	/**
	 * Get Response as String
	 *
	 * @return string|bool
	 */
	public function getResponse(): string|bool {
		return $this->xml->asXML() ?? false;
	}

	/**
	 * Get Result Code
	 *
	 * @return string
	 */
	public function getResultCode(): string {
		return $this->xml->messages->resultCode;
	}

	/**
	 * Get Messages
	 *
	 * @return array
	 */
	public function getMessages(): array {
		$msgs = [];

		foreach($this->xml->messages as $msg) {
			$data = [
				"code" => $msg->code,
				"message" => $msg->message
			];

			$msgs[] = $data;
		}

		return $msgs;
	}

	/**
	 * Get Customer Profile ID
	 *
	 * @return ?string
	 */
	public function getCustomerProfileId(): ?string {
		if($this->xml->customerProfileId) {
			return $this->xml->customerProfileId;
		} else {
			return null;
		}
	}

	/**
	 * Get Customer Payment Profile ID
	 *
	 * @return string|null
	 */
	public function getCustomerPaymentProfileId(): ?string {
		if($this->xml->customerPaymentProfileId) {
			return $this->xml->customerPaymentProfileId;
		} else {
			return null;
		}
	}

	/**
	 * Get Customer Shipping Address ID
	 *
	 * @return string|null
	 */
	public function getCustomerShippingProfileId(): ?string {
		if($this->xml->customerAddressId) {
			return $this->xml->customerAddressId;
		} else {
			return null;
		}
	}

	/**
	 * Get Customer Data Created by Transaction
	 *
	 * @return array|null
	 */
	public function getCustomerFromTransaction(): ?array {
		if($this->xml->customerProfileId && $this->xml->customerPaymentProfileIdList && $this->xml->customerShippingAddressIdList) {
			$arr = [
				"customerProfileId" => $this->xml->customerProfileId,
				"customerPaymentProfileIdList" => [],
				"customerShippingAddressIdList" => []
			];

			foreach($this->xml->customerPaymentProfileIdList as $payment) {
				$arr["customerPaymentProfileIdList"][] = $payment->numericString;
			}

			foreach($this->xml->customerShippingAddressIdList as $shipping) {
				$arr["customerShippingAddressIdList"][] = $shipping;
			}

			return $arr;
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction Response
	 *
	 * @return array|null
	 */
	public function getTransactionResponse(): ?array {
		if($this->xml->transactionResponse) {
			return json_decode(json_encode($this->xml->transactionResponse), true);
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction ID
	 *
	 * @return string|null
	 */
	public function getTransactionId(): ?string {
		if($this->xml->transactionResponse->transId) {
			return $this->xml->transactionResponse->transId;
		} else {
			return null;
		}
	}

	/**
	 * Get Auth Code
	 *
	 * @return string|null
	 */
	public function getAuthCode(): ?string {
		if($this->xml->transactionResponse->authCode) {
			return $this->xml->transactionResponse->authCode;
		} else {
			return null;
		}
	}

	/**
	 * Get All Customer IDs
	 *
	 * @return ?array
	 */
	public function getAllCustomerIds(): ?array {
		$list = [];

		if($this->xml->ids) {
			foreach($this->xml->ids as $profile) {
				foreach($profile->numericString as $id) {
					$list[] = (string)$id;
				}
			}

			return $list;
		} else {
			return null;
		}
	}

	/**
	 * Get Customer Profile
	 *
	 * @return array|null
	 */
	public function getCustomer(): ?array {
		if($this->xml->profile) {
			return json_decode(json_encode($this->xml->profile), true);
		} else {
			return null;
		}
	}

	/**
	 * Get Batch List for Reporting
	 *
	 * @return array|null
	 */
	public function getBatchList(): ?array {
		if($this->xml->batchList) {
			$arr = [];

			foreach($this->xml->batchList as $item) {
				$arr[] = json_decode(json_encode($item), true);
			}

			return $arr;
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction List
	 *
	 * @return array|null
	 */
	public function getTransactionList(): ? array {
		if($this->xml->transactions) {
			$arr = [];

			foreach($this->xml->transactions as $transaction) {
				$arr[] = json_decode(json_encode($transaction), true);
			}

			return $arr;
		} else {
			return null;
		}
	}

	/**
	 * Get Transaction Details
	 *
	 * @return array|null
	 */
	public function getTransactionDetails(): ?array {
		if($this->xml->transaction) {
			return json_decode(json_encode($this->xml->transaction), true);
		} else {
			return null;
		}
	}

	/**
	 * Get Batch Details
	 *
	 * @return array|null
	 */
	public function getBatchDetails(): ?array {
		if($this->xml->batch) {
			return json_decode(json_encode($this->xml->batch), true);
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
		if($this->xml->isTestMode) {
			$arr = [
				"isTestMode" => $this->xml->isTestMode ?? "",
				"processors" => [],
				"merchantName" => $this->xml->merchantName ?? "",
				"gatewayId" => $this->xml->gatewayId ?? "",
				"marketTypes" => [],
				"productCodes" => [],
				"paymentMethods" => [],
				"currencies" => [],
				"publicClientKey" => $this->xml->publicClientKey ?? ""
			];

			foreach($this->xml->marketTypes as $type) {
				$arr["marketTypes"][] = json_decode(json_encode($type), true);
			}

			foreach($this->xml->productCodes as $code) {
				$arr["productCodes"][] = json_decode(json_encode($code), true);
			}

			foreach($this->xml->paymentMethods as $pay) {
				$arr["paymentMethods"][] = json_decode(json_encode($pay), true);
			}

			foreach($this->xml->currencies as $cur) {
				$arr["currencies"][] = json_decode(json_encode($cur), true);
			}

			return $arr;
		} else {
			return null;
		}
	}

	/**
	 * Response to Array
	 *
	 * @return array
	 */
	public function toArray(): array {
		return json_decode(json_encode($this->xml), true);
	}
}