<?php

namespace Incubateiq\Gateway\Transaction;

use Incubateiq\Gateway\Transaction as Library;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as Constants;

class ProfileTransaction {
	/**
	 * Merchant Authentication
	 *
	 * @var AnetAPI\MerchantAuthenticationType
	 */
	protected AnetAPI\MerchantAuthenticationType $merchant;

	/**
	 * Profile Transaction Object
	 *
	 * @var ProfileTransactionObject
	 */
	protected Library\ProfileTransactionObject $object;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	private string $refID;

	public function __construct(Library\ProfileTransactionObject $object) {
		$this->object = $object;

		$merchant = new Library\Merchant();
		$this->merchant = $merchant->getMerchant();
	}

	/**
	 * Pay By Profile ID
	 *
	 * @return array
	 */
	public function payByProfile(): array {
		$profile = new AnetAPI\CustomerProfilePaymentType();
		$profile->setCustomerProfileId($this->object->__get('profileId'));

		$paymentProfile = new AnetAPI\PaymentProfileType();
		$paymentProfile->setPaymentProfileId($this->object->__get('paymentProfileId'));
		$profile->setPaymentProfile($paymentProfile);

		$transaction = new AnetAPI\TransactionRequestType();
		$transaction->setTransactionType($this->object->__get('transactionType'));
		$transaction->setAmount($this->object->__get('amount'));
		$transaction->setProfile($profile);
		$transaction->setShipTo($this->setShippingInfo($this->object->__get('shipping')));

		if(count($this->object->__get('shipping_cost')) > 0) {
			$transaction->setShipping($this->setShippingCost($this->object->__get('shipping_cost')));
		}

		if(count($this->object->__get('tax')) > 0) {
			$transaction->setTax($this->setTaxCost($this->object->__get('tax')));
		}

		if(count($this->object->__get('items')) > 0) {
			$transaction->setLineItems($this->packageItems($this->object->__get('items')));
		}

		$this->setRefId();

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchant);
		$request->setRefId($this->refID);
		$request->setTransactionRequest($transaction);

		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse(Constants\ANetEnvironment::SANDBOX);

		$return = [];

		if($response != null && $response->getMessages()->getResultCode() == 'Ok') {
			$tr = $response->getTransactionResponse();

			if($tr != null && $tr->getMessages() != null) {
				$return['response'] = $tr->getResponseCode();
				$return['authCode'] = $tr->getAuthCode();
				$return['transactionId'] = $tr->getTransId();
				$return['code'] = $tr->getMessages()[0]->getCode();
				$return['description'] = $tr->getMessages()[0]->getDescription();
			} else {
				$return['errorCode'] = $tr->getErrors()[0]->getErrorCode();
				$return['error'] = $tr->getErrors()[0]->getErrorText();
			}
		} else {
			$return['errorCode'] = '000000';
			$return['error'] = 'Server failed to respond.';
		}

		return $return;
	}

	/**
	 * Set Unique Reference ID
	 *
	 * @return void
	 */
	private function setRefId(): void {
		// Ensures all transactions have unique reference numbers
		$this->refID = $this->object->__get('customerId') . (string)time();
	}

	/**
	 * Set Shipping Cost
	 *
	 * @param array $shipping_data
	 *
	 * @return AnetAPI\ExtendedAmountType
	 */
	private function setShippingCost(array $shipping_data): AnetAPI\ExtendedAmountType {
		$shipping_object = new AnetAPI\ExtendedAmountType();
		$shipping_object->setAmount($shipping_data['amount']);
		$shipping_object->setName($shipping_data['name']);
		$shipping_object->setDescription($shipping_data['description']);

		return $shipping_object;
	}

	/**
	 * Set Tax Cost
	 *
	 * @param array $tax_data
	 *
	 * @return AnetAPI\ExtendedAmountType
	 */
	private function setTaxCost(array $tax_data): AnetAPI\ExtendedAmountType {
		$tax_object = new AnetAPI\ExtendedAmountType();
		$tax_object->setAmount($tax_data['amount']);
		$tax_object->setName($tax_data['name']);
		$tax_object->setDescription($tax_data['description']);

		return $tax_object;
	}

	/**
	 * Package Items
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	private function packageItems(array $items): array {
		$lineItems = [];

		foreach($items as $item) {
			$container = new AnetAPI\LineItemType();

			$container->setItemId($item['id']);
			$container->setName($item['name']);
			$container->setDescription($item['description']);
			$container->setQuantity($item['quantity']);
			$container->setUnitPrice($item['price']);

			$lineItems[] = $container;
		}

		return $lineItems;
	}

	/**
	 * Set Shipping Info
	 *
	 * @param array $shipping
	 *
	 * @return AnetAPI\CustomerAddressType
	 */
	private function setShippingInfo(array $shipping): AnetAPI\CustomerAddressType {
		$customerShipping = new AnetAPI\CustomerAddressType();

		$customerShipping->setFirstName($shipping['firstName']);
		$customerShipping->setLastName($shipping['lastName']);
		$customerShipping->setAddress($shipping['address']);
		$customerShipping->setCity($shipping['city']);
		$customerShipping->setState($shipping['state']);
		$customerShipping->setZip($shipping['zip']);
		$customerShipping->setCountry($shipping['country']);
		$customerShipping->setCompany($shipping['company']);

		return $customerShipping;
	}
}