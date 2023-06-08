<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Objects as Objects;
use ErrorException;

class Customer {
	/**
	 * Customer
	 *
	 * @var Objects\UpdateProfileObject | Objects\ChargeCustomerObject | Objects\CustomerProfileObject | Objects\CustomerPaymentObject | Objects\CustomerShippingObject | null
	 */
	protected Objects\UpdateProfileObject|Objects\ChargeCustomerObject|Objects\CustomerProfileObject|Objects\CustomerPaymentObject|Objects\CustomerShippingObject|null $customer;

	public function __construct(
		Objects\UpdateProfileObject|
		Objects\ChargeCustomerObject|
		Objects\CustomerProfileObject|
		Objects\CustomerPaymentObject|
		Objects\CustomerShippingObject $customer=null,
		string $customerId=null
	) {
		if($customerId) {
			return $this->load($customerId);
		} else {
			$this->customer = $customer;
		}
	}

	/**
	 * Load Customer Profile
	 *
	 * @param string $profileId
	 *
	 * @return array|null Customer Profile Array
	 */
	public function load(string $profileId): ?array {
		$arr = [
			"customerProfileId" => $profileId,
			"includeIssuerInfo" => true
		];

		$builder = new Library\Builder("getCustomerProfileRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getCustomer();
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Profile
	 *
	 * @return string|null Customer Profile ID
	 *
	 * @throws ErrorException
	 */
	public function createCustomer(): ?string {
		if(!$this->customer instanceof Objects\CustomerProfileObject) {
			throw new ErrorException("You need to use Objects\CustomerProfileObject, you are using " . get_class($this->customer) . ".");
		}

		$builder = new Library\Builder("createCustomerProfileRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getCustomerProfileId();
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Payment Profile
	 *
	 * @return string|null Customer Payment Profile ID
	 *
	 * @throws ErrorException
	 */
	public function createCustomerPaymentProfile(): ?string {
		if(!$this->customer instanceof Objects\CustomerPaymentObject) {
			throw new ErrorException("You need to use Objects\CustomerPaymentObject, you are using " . get_class($this->customer) . ".");
		}

		$builder = new Library\Builder("createCustomerPaymentProfileRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getCustomerPaymentProfileId();
		} else {
			return null;
		}
	}

	/**
	 * Create Customer Shipping Profile
	 *
	 * @return string|null
	 *
	 * @throws ErrorException
	 */
	public function createCustomerShippingProfile(): ?string {
		if(!$this->customer instanceof Objects\CustomerShippingObject) {
			throw new ErrorException("You need to use Objects\CustomerShippingObject, you are using " . get_class($this->customer) . ".");
		}

		$builder = new Library\Builder("createCustomerShippingAddressRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getCustomerShippingProfileId();
		} else {
			return null;
		}
	}

	/**
	 * Get All Customers from Merchant
	 *
	 * @return array|null
	 */
	public function getAllCustomers(): ?array {
		$builder = new Library\Builder("getCustomerProfileIdsRequest");
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getAllCustomerIds();
		} else {
			return null;
		}
	}

	/**
	 * Update Customer Profile
	 *
	 * @return bool
	 *
	 * @throws ErrorException
	 */
	public function updateCustomerProfile(): bool {
		if(!$this->customer instanceof Objects\UpdateProfileObject) {
			throw new ErrorException("You need to use Objects\UpdateProfileObject, you are using " . get_class($this->customer) . ".");
		}

		$builder = new Library\Builder("updateCustomerProfileRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
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
	 * @return bool
	 * @throws ErrorException
	 */
	public function updatePaymentProfile(): bool {
		if(!$this->customer instanceof Objects\CustomerPaymentObject) {
			throw new ErrorException("You need to use Objects\CustomerPaymentObject, you are using " . get_class($this->customer));
		}

		$builder = new Library\Builder("updateCustomerPaymentProfileRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update Shipping Profile
	 *
	 * @return bool
	 * @throws ErrorException
	 */
	public function updateShippingProfile(): bool {
		if(!$this->customer instanceof Objects\CustomerPaymentObject) {
			throw new ErrorException("You need to use Objects\CustomerPaymentObject, you are using " . get_class($this->customer));
		}

		$builder = new Library\Builder("updateCustomerShippingAddressRequest", $this->customer->getData());
		$request = new Library\Request($builder->getRequest());
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
		$arr = [
			"customerProfileId" => $profile
		];

		$builder = new Library\Builder("deleteCustomerProfileRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete Payment Profile
	 *
	 * @param string $profile
	 * @param string $paymentProfile
	 *
	 * @return bool
	 */
	public function deletePaymentProfile(string $profile, string $paymentProfile): bool {
		$arr = [
			"customerProfileId" => $profile,
			"customerPaymentProfileId" => $paymentProfile
		];

		$builder = new Library\Builder("deleteCustomerPaymentProfileRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete Shipping Profile
	 *
	 * @param string $profile
	 * @param string $shippingProfile
	 *
	 * @return bool
	 */
	public function deleteShippingProfile(string $profile, string $shippingProfile): bool {
		$arr = [
			"customerProfileId" => $profile,
			"customerAddressId" => $shippingProfile
		];

		$builder = new Library\Builder("deleteCustomerShippingAddressRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Create Customer from Transaction
	 *
	 * @param string $transId
	 *
	 * @return array|null
	 */
	public function createCustomerFromTransaction(string $transId): ?array {
		$arr = [
			"transId" => $transId
		];

		$builder = new Library\Builder("createCustomerProfileFromTransactionRequest", $arr);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getCustomerFromTransaction();
		} else {
			return null;
		}
	}

	/**
	 * Charge Customer Profile
	 *
	 * @param string $profileId
	 * @param string $paymentProfile
	 *
	 * @return array|null
	 *
	 * @throws ErrorException
	 */
	public function chargeCustomerProfile(string $profileId, string $paymentProfile): ?array {
		if(!($this->customer instanceof Objects\ChargeCustomerObject)) {
			throw new ErrorException("You need to use Objects\ChargeCustomerObject, not " . get_class($this->customer));
		}

		$builder = new Library\Builder("createTransactionRequest", $this->customer);
		$request = new Library\Request($builder->getRequest());
		$response = $request->process();

		if($response->getResultCode() == "Ok") {
			return $response->getTransactionResponse();
		} else {
			return null;
		}
	}
}