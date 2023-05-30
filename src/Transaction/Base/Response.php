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
	 * Response to Array
	 *
	 * @return array
	 */
	public function toArray(): array {
		return json_decode(json_encode($this->xml), true);
	}
}