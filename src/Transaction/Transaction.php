<?php

namespace Incubateiq\Gateway\Transaction;

use Incubateiq\Gateway\Transaction as Library;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as Constants;

class Transaction {
	/**
	 * Transaction Object
	 *
	 * @var TransactionObject
	 */
	protected Library\TransactionObject $object;

	/**
	 * Payment Type Object
	 *
	 * @var AnetAPI\PaymentType
	 */
	protected AnetAPI\PaymentType $payment;

	/**
	 * Merchant Object
	 *
	 * @var AnetAPI\MerchantAuthenticationType
	 */
	protected AnetAPI\MerchantAuthenticationType $merchant;

	/**
	 * Transaction Request Type
	 *
	 * @var AnetAPI\TransactionRequestType
	 */
	protected AnetAPI\TransactionRequestType $transaction;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	private string $refID = '';

	public function __construct(TransactionObject $object) {
		$this->object = $object;
		$merchant = new Library\Merchant();
		$this->merchant = $merchant->getMerchant();

		$this->setRefId();
		$this->payment = $this->setCardInfo();
		$this->transaction = $this->setTransaction();
	}

	/**
	 * Set Unique Reference ID
	 *
	 * @return void
	 */
	private function setRefId(): void {
		// Ensures all transactions have unique reference numbers
		$this->refID = $this->object->__get('customerID') . (string)time();
	}

	/**
	 * Set Order Info
	 *
	 * @return AnetAPI\OrderType
	 */
	private function setOrderInfo(): AnetAPI\OrderType {
		$order = new AnetAPI\OrderType();
		$order->setInvoiceNumber($this->refID);
		$order->setDescription('Your Order');

		return $order;
	}

	/**
	 * Set Transaction Type
	 *
	 * @return AnetAPI\TransactionRequestType
	 */
	private function setTransaction(): AnetAPI\TransactionRequestType {
		$this->transaction = new AnetAPI\TransactionRequestType();
		$this->transaction->setTransactionType('authCaptureTransaction');
		$this->transaction->setAmount($this->object->__get('amount'));
		$this->transaction->setPayment($this->payment);
		$this->transaction->setBillTo($this->setCustomerAddress());
		$this->transaction->setCustomer($this->setCustomerData());
		$this->transaction->setShipTo($this->setShippingInfo());
		$this->transaction->setOrder($this->setOrderInfo());

		if(count($this->object->__get('shipping_cost')) > 0) {
			$this->transaction->setShipping($this->setShippingCost());
		}

		if(count($this->object->__get('tax')) > 0) {
			$this->transaction->setTax($this->setTaxCost());
		}

		if(count($this->object->__get('items')) > 0) {
			$this->transaction->setLineItems($this->packageItems());
		}

		return $this->transaction;
	}

	/**
	 * Create Shipping Object
	 *
	 * @param array|null $shipping_data
	 *
	 * @return AnetAPI\ExtendedAmountType
	 */
	private function setShippingCost(?array $shipping_data=null): AnetAPI\ExtendedAmountType {
		if(!$shipping_data) {
			$shipping_data = $this->object->__get('shipping_cost');
		}

		$shipping_object = new AnetAPI\ExtendedAmountType();
		$shipping_object->setAmount($shipping_data['amount']);
		$shipping_object->setName($shipping_data['name']);
		$shipping_object->setDescription($shipping_data['description']);

		return $shipping_object;
	}

	/**
	 * Create Tax Cost Object
	 *
	 * @param array|null $tax_data
	 *
	 * @return AnetAPI\ExtendedAmountType
	 */
	private function setTaxCost(?array $tax_data=null): AnetAPI\ExtendedAmountType {
		if(!$tax_data) {
			$tax_data = $this->object->__get('tax');
		}

		$tax_object = new AnetAPI\ExtendedAmountType();
		$tax_object->setAmount($tax_data['amount']);
		$tax_object->setName($tax_data['name']);
		$tax_object->setDescription($tax_data['description']);

		return $tax_object;
	}

	/**
	 * Package Line Items
	 *
	 * @param array|null $items
	 *
	 * @return array
	 */
	private function packageItems(?array $items=null): array {
		if(!$items) {
			$items = $this->object->__get('items');
		}

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
	 * Set Payment Type (Card)
	 *
	 * @return AnetAPI\PaymentType
	 */
	private function setCardInfo(): AnetAPI\PaymentType {
		$card = new AnetAPI\CreditCardType();
		$card->setCardNumber($this->object->__get('cardNumber'));
		$card->setExpirationDate($this->object->__get('expiration'));
		$card->setCardCode($this->object->__get('cvv'));

		$this->payment = new AnetAPI\PaymentType();
		$this->payment->setCreditCard($card);

		return $this->payment;
	}

	/**
	 * Set Customer Address
	 *
	 * @return AnetAPI\CustomerAddressType
	 */
	private function setCustomerAddress(): AnetAPI\CustomerAddressType {
		$customer = new AnetAPI\CustomerAddressType();
		$billing = $this->object->__get('billing');

		// Set Customer Company (B2B)
		//$customer->setCompany('COMPANY NAME HERE');

        $customer->setFirstName($billing['firstName']);
        $customer->setLastName($billing['lastName']);
        $customer->setAddress($billing['address']);
		$customer->setCity($billing['city']);
		$customer->setState($billing['state']);
		$customer->setZip($billing['zip']);
		$customer->setPhoneNumber($billing['phone']);
		$customer->setFaxNumber($billing['fax']);
		$customer->setCompany($billing['company']);

		// Change for outside USA
		$customer->setCountry($billing['country']);

        return $customer;
	}

	/**
	 * Set Customer Info
	 *
	 * @return AnetAPI\CustomerDataType
	 */
	private function setCustomerData(): AnetAPI\CustomerDataType {
		$customerData = new AnetAPI\CustomerDataType();
		$customerData->setId($this->object->__get('customerID'));
		$customerData->setEmail($this->object->__get('email'));

		return $customerData;
	}

	/**
	 * Set Customer Shipping Info
	 *
	 * @return AnetAPI\CustomerAddressType
	 */
	private function setShippingInfo(): AnetAPI\CustomerAddressType {
		$shipping = $this->object->__get('shipping');

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

	/**
	 * Execute Transaction
	 *
	 * @return array
	 */
	public function pay(): array {
		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($this->merchant);

		$request->setTransactionRequest($this->transaction);

		$controller = new AnetController\CreateTransactionController($request);

		$response = $controller->executeWithApiResponse(Constants\ANetEnvironment::SANDBOX);

		$return = [];

		if($response != null && $response->getMessages()->getResultCode() == "Ok") {
			$transactionResponse = $response->getTransactionResponse();

			if(($transactionResponse !== null) && ($transactionResponse->getResponseCode() == "1")) {
				$return['transactionId'] = $transactionResponse->getTransId();
				$return['authCode'] = $transactionResponse->getAuthCode();
				$return['message'] = 'Transaction Approved';
				$return['success'] = true;

				if($this->object->__get('save_card')) {
					$profileObject = new Library\CustomerProfile($this->merchant);
					$profile = $profileObject->create($this->object, $this->refID);

					// TODO: Store Profile ID

					$return['profile'] = $profile;
				}
			} else {
				$return['message'] = "Transaction Error: {$response->getMessages()->getMessage()[0]->getText()}";
				$return['code'] = $response->getMessages()->getMessage()[0]->getCode();
			}
		} else {
			$return['message'] = "Transaction Error: Invalid Transaction";
			$return['code'] = $response->getMessages()->getMessage()[0]->getCode();
		}

		return $return;
    }
}