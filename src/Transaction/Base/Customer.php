<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Env as Env;

use SimpleXMLElement as XML;

class Customer {
	/**
	 * Customer Object
	 *
	 * @var CustomerObject
	 */
	protected Library\CustomerObject $customer;

	/**
	 * XML Request
	 *
	 * @var XML
	 */
	protected XML $xml;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	private string $ref;

	/**
	 * Check if Profile is Loaded
	 *
	 * @var bool
	 */
	private bool $isLoaded = false;

	public function __construct(Library\CustomerObject $customer=null) {
		if($customer) {
			$this->customer = $customer;

			$this->isLoaded = true;
		}
	}

	/**
	 * Set Merchant Authentication Object
	 *
	 * @return void
	 */
	private function setMerchantAuthentication(): void {
		$merchant = $this->xml->addChild("merchantAuthentication");
		$merchant->addChild("name", Env::ID);
		$merchant->addChild("transactionKey", Env::KEY);
	}

	/**
	 * Set Reference ID
	 *
	 * @param string $ref
	 *
	 * @return void
	 */
	private function setRefId(string $ref): void {
		$this->xml->addChild("refId", $ref . time());
	}

	/**
	 * Is Loaded with Customer Data
	 *
	 * @return bool
	 */
	private function isLoaded(): bool {
		return $this->isLoaded;
	}

	/**
	 * Set Bill To/Ship To
	 * 		Defaults to billTo
	 *
	 * @param XML $parent
	 * @param string $type
	 * @param array $info
	 *
	 * @return void
	 */
	private function setBillToShipTo(XML $parent, array $info, string $type="billTo"): void {
		$ext = $parent->addChild($type);

		$ext->addChild("firstName", $info["firstName"]);
		$ext->addChild("lastName", $info["lastName"]);
		$ext->addChild("company", $info["company"] ?? "");
		$ext->addChild("address", $info["address"]);
		$ext->addChild("city", $info["city"]);
		$ext->addChild("state", $info["state"]);
		$ext->addChild("zip", $info["zip"]);
		$ext->addChild("country", $info["country"] ?? "US");
		$ext->addChild("phoneNumber", $info["phoneNumber"]);
		$ext->addChild("faxNumber", $info["fax"] ?? "");
	}

	/**
	 * Load Customer Profile
	 *
	 * @param string $profileId
	 *
	 * @return ?Library\Customer
	 */
	public function load(string $profileId): ?Customer {
		$this->setRequestParent("getCustomerProfileRequest");

		$this->setMerchantAuthentication();

		$this->xml->addChild("customerProfileId", $profileId);
		$this->xml->addChild("includeIssuerInfo", true);

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			$this->customer = new Library\CustomerObject($response->getCustomer());

			return $this;
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Profile
	 *
	 * @param CreateCustomerObject $user
	 *
	 * @return ?Library\Response
	 */
	public function createCustomer(Library\CreateCustomerObject $user): ?Library\Response {
		$this->setRequestParent("createCustomerProfileRequest");

		$this->setMerchantAuthentication();
		$this->setRefId('cr');

		$profile = $this->xml->addChild("profile");
		$profile->addChild("merchantCustomerId", $user->__get("customerId"));
		$profile->addChild("description", $user->__get("firstName") . " " . $user->__get("lastName"));
		$profile->addChild("email", $user->__get("email"));

		$paymentProfiles = $profile->addChild("paymentProfiles");
		$paymentProfiles->addChild("customerType", $user->__get("customerType"));

		$creditCard_data = $user->__get("paymentProfiles")["creditCard"];

		$payment = $paymentProfiles->addChild("payment");

		$creditCard = $payment->addChild("creditCard");
		$creditCard->addChild("cardNumber", $creditCard_data["cardNumber"]);
		$creditCard->addChild("expirationDate", $creditCard_data["expiration"]);

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Payment Profile
	 *
	 * @param string $profile
	 * @param CreateCustomerObject $data
	 *
	 * @return ?Library\Response
	 */
	public function createCustomerPaymentProfile(string $profile, Library\CreateCustomerObject $data): ?Library\Response {
		$this->setRequestParent("createCustomerPaymentProfileRequest");

		$this->setMerchantAuthentication();

		$this->xml->addChild("customerProfileId", $profile);

		$paymentProfile = $this->xml->addChild("paymentProfile");

		$billTo_data = $data->__get('billTo');

		$this->setBillToShipTo($paymentProfile, $billTo_data);

		$cc = $data->__get('paymentProfiles')["creditCard"];

		$payment = $paymentProfile->addChild("payment");
		$creditCard =  $payment->addChild("creditCard");
		$creditCard->addChild("cardNumber", $cc["cardNumber"]);
		$creditCard->addChild("expirationDate", $cc["expiration"]);

		$paymentProfile->addChild("defaultPaymentProfile", $data->__get("default"));

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Shipping Profile
	 *
	 * @param string $profile
	 * @param CreateCustomerObject $customer
	 *
	 * @return ?Library\Response
	 */
	public function createCustomerShippingProfile(string $profile, Library\CreateCustomerObject $customer): ?Library\Response {
		$this->setRequestParent("createCustomerShippingAddressRequest");
		$this->setMerchantAuthentication();
		$this->xml->addChild("customerProfileId", $profile);
		$this->setBillToShipTo($this->xml, $customer->__get("billTo"), "address");

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Get All Customers from Merchant
	 *
	 * @return ?Library\Response
	 */
	public function getAllCustomers(): ?Library\Response{
		$this->setRequestParent("getCustomerProfileIdsRequest");
		$this->setMerchantAuthentication();

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Update Customer Profile
	 *
	 * @param string $profile
	 * @param ProfileObject $data
	 *
	 * @return bool
	 */
	public function updateCustomerProfile(string $profile, Library\ProfileObject $data): bool {
		$this->setRequestParent("updateCustomerProfileRequest");
		$this->setMerchantAuthentication();

		$profile = $this->xml->addChild("profile");
		$profile->addChild("merchantCustomerId", $data->__get("merchantCustomerId"));
		$profile->addChild("description", $data->__get("description"));
		$profile->addChild("email", $data->__get("email"));
		$profile->addChild('customerProfileId', $data->__get("customerProfileId"));

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete Customer Profile
	 *
	 * @param string $profile
	 *
	 * @return bool
	 */
	public function deleteCustomerProfile(string $profile): bool {
		$this->setRequestParent("deleteCustomerProfileRequest");
		$this->setMerchantAuthentication();
		$this->xml->addChild("customerProfileId", $profile);

		$request = new Library\Request($this->xml);
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update Payment Profile
	 *
	 * @param string $profile
	 * @param PaymentProfileObject $data
	 *
	 * @return void
	 */
	public function updatePaymentProfile(string $profile, Library\PaymentProfileObject $data) {
		$this->setRequestParent("updateCustomerPaymentProfileRequest");
		$this->setMerchantAuthentication();
		$this->xml->addChild("customerProfileId", $data->__get("customerProf"));

		$paymentProfile = $this->xml->addChild("paymentProfile");
		$pp = $data->__get("paymentProfile");

		$bill = $pp["billTo"];
		$billTo = $paymentProfile->addChild("billTo");
		$this->setBillToShipTo($billTo, $bill);

		$cc = $pp["payment"]["creditCard"];
		$payment = $paymentProfile->addChild("payment");
		$creditCard = $payment->addChild("creditCard");
		$creditCard->addChild("cardNumber", $cc["cardNumber"]);
		$creditCard->addChild("expirationDate", $cc["expirationDate"]);

		$paymentProfile->addChild("defaultPaymentProfile", $pp["defaultPaymentProfile"]);
		$paymentProfile->addChild("customerPaymentProfileId", $pp["customerPaymentProfileId"]);
	}

	/**
	 * Set XML Parent and Schema
	 *
	 * @param string $parent
	 *
	 * @return void
	 */
	private function setRequestParent(string $parent): void {
		$this->xml = new XML("<" . $parent . "></" . $parent . ">");
		$this->xml->addAttribute('xmlns', "AnetApi/xml/v1/schema/AnetApiSchema.xsd");
	}

	/**
	 * Debug Customer Data
	 *
	 * @return array
	 */
	public function debugCustomer(): array {
		return $this->customer->getTransactionData();
	}
}