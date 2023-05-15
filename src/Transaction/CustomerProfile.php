<?php

namespace Incubateiq\Gateway\Transaction;

use Incubateiq\Gateway\Transaction as Library;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as Constants;

class CustomerProfile {
	/**
	 * Customer Profile ID
	 *
	 * @var string
	 */
	protected string $profile;

	/**
	 * Merchant Account Object
	 *
	 * @var AnetAPI\MerchantAuthenticationType
	 */
	protected AnetAPI\MerchantAuthenticationType $merchant;

	public function __construct() {
		$merchant = new Library\Merchant();
		$this->merchant = $merchant->getMerchant();
	}

	/**
	 * Create Customer Profile and Return Profile ID
	 *
	 * @param Library\TransactionObject $obj
	 * @param string $ref
	 *
	 * @return string|null
	 */
	public function create(Library\TransactionObject $obj, string $ref): ?string {
		$payment = new AnetAPI\CustomerPaymentProfileType();
		$payment->setCustomerType('individual');
		$payment->setbillTo($this->setBillTo($obj->__get('billing')));
		$payment->setPayment($this->setPaymentProfile($obj));
		$paymentProfiles[] = $payment;

		$customer = new AnetAPI\CustomerProfileType();
		$data = $obj->__get('billing');
		$customer->setDescription($data['firstName'] . ' ' . $data['lastName']);
		$customer->setMerchantCustomerId($obj->__get('customerID'));
		$customer->setEmail($obj->__get('email'));
		$customer->setPaymentProfiles($paymentProfiles);

		$request = new AnetAPI\CreateCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchant);
		$request->setRefId($ref);
		$request->setProfile($customer);

		$controller = new AnetController\CreateCustomerProfileController($request);
		$response = $controller->executeWithApiResponse(Constants\ANetEnvironment::SANDBOX);

		if($response != null && $response->getMessages()->getResultCode() == 'Ok') {
			return $response->getCustomerProfileId();
		} else {
			return null;
		}
	}

	/**
	 * Set Payment
	 *
	 * @param TransactionObject $obj
	 *
	 * @return AnetAPI\PaymentType
	 */
	private function setPaymentProfile(Library\TransactionObject $obj): AnetAPI\PaymentType {
		$card = new AnetAPI\CreditCardType();
		$card->setCardNumber($obj->__get('cardNumber'));
		$card->setExpirationDate($obj->__get('expiration'));
		$card->setCardCode($obj->__get('cvv'));

		$payment = new AnetAPI\PaymentType();
		$payment->setCreditCard($card);

		return $payment;
	}

	/**
	 * Set Billing
	 *
	 * @param array $address
	 *
	 * @return AnetAPI\CustomerAddressType
	 */
	private function setBillTo(array $address): AnetAPI\CustomerAddressType {
		$bill = new AnetAPI\CustomerAddressType();

		$bill->setFirstName($address['firstName']);
		$bill->setLastName($address['lastName']);
		$bill->setCompany($address['company'] ?? '');
		$bill->setAddress($address['address']);
		$bill->setCity($address['city']);
		$bill->setState($address['state']);
		$bill->setZip($address['zip']);
		$bill->setCountry($address['country'] ?? '');

		return $bill;
	}

	/**
	 * Get Customer Profile
	 *
	 * @param string $profile
	 *
	 * @return AnetAPI\CustomerProfileType|null
	 */
	public function getProfile(string $profile): ?AnetAPI\CustomerProfileMaskedType {
		$request = new AnetAPI\GetCustomerProfileRequest();
		$request->setMerchantAuthentication($this->merchant);
		$request->setCustomerProfileId($profile);

		$controller = new AnetController\GetCustomerProfileController($request);
		$response = $controller->executeWithApiResponse(Constants\ANetEnvironment::SANDBOX);

		if($response != null && $response->getMessages()->getResultCode() == 'Ok') {
			return $response->getProfile();
		} else {
			return null;
		}
	}
}