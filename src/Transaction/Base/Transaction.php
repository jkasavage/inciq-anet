<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base as Library;
use Incubateiq\Gateway\Transaction\Base\Env as Constants;
use SimpleXMLElement as XML;

class Transaction {
	/**
	 * Transaction Object
	 *
	 * @var TransactionObject
	 */
	protected Library\TransactionObject $obj;

	/**
	 * Loaded XML
	 *
	 * @var XMl
	 */
	protected XML $xml;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	protected string $ref;

	public function __construct(Library\TransactionObject $obj) {
		$this->obj = $obj;

		$this->xml = new XML('<createTransactionRequest></createTransactionRequest>');
		$this->xml->addAttribute("xmlns", "AnetApi/xml/v1/schema/AnetApiSchema.xsd");
	}

	/**
	 * Process Payment
	 *
	 * @return Transaction
	 */
	public function transaction(): Transaction {
		return $this->createTransactionRequest();
	}

	/**
	 * Process Refund
	 *
	 * @return $this
	 */
	public function refund(): Transaction {
		return $this->createRefundRequest();
	}

	/**
	 * Process Void Transaction
	 *
	 * @return Transaction
	 */
	public function void(): Transaction {
		return $this->createVoidRequest();
	}

	/**
	 * Set Merchant Details
	 *
	 * @return Transaction
	 */
	private function setMerchantDetails(): Transaction {
		$merchant = $this->xml->addChild('merchantAuthentication');

		$merchant->addChild('name', Constants::ID);
		$merchant->addChild('transactionKey', Constants::KEY);

		return $this;
	}

	/**
	 * Set Reference ID
	 *
	 * @param string $ref
	 *
	 * @return Transaction
	 */
	private function setReferenceId(string $ref): Transaction {
		$this->ref = $ref . time();

		return $this;
	}

	/**
	 * Set Transaction Details
	 *
	 * @return Transaction
	 */
	private function setTransactionDetails(): Transaction {
		$this->xml->addChild("refId", $this->ref);

		$this->xml->transactionRequest->transactionType = 'authCaptureTransaction';
		$this->xml->transactionRequest->amount = $this->obj->__get('amount');
		$this->xml->transactionRequest->currencyCode = "USD";

		return $this;
	}

	/**
	 * Set Refund Details
	 *
	 * @return Transaction
	 */
	private function setRefundDetails(): Transaction {
		$this->xml->addChild("refId", $this->ref);

		$refund = $this->xml->addChild("transactionRequest");
		$refund->addChild("transactionType", "refundTransaction");
		$refund->addChild("amount", $this->obj->__get('amount'));

		$payment = $refund->addChild("payment");
		$cc = $payment->addChild("creditCard");
		$cc->addChild("cardNumber", substr($this->obj->__get('cardNumber'), -4));
		$cc->addChild("expirationDate", $this->obj->__get("expiration"));

		$refund->addChild("refTransId", $this->obj->__get("transactionId"));

		return $this;
	}

	/**
	 * Set Void Details
	 *
	 * @return Transaction
	 */
	private function setVoidDetails(): Transaction {
		$this->xml->addChild("refId", $this->ref);

		$void = $this->xml->addChild("transactionRequest");
		$void->addChild("transactionType", "voidTransaction");
		$void->addChild("refTransId", $this->obj->__get('transactionId'));

		return $this;
	}

	/**
	 * Set Credit Card Details
	 *
	 * @return Transaction
	 */
	private function setCreditCardDetails(): Transaction {
		$payment = $this->xml->transactionRequest->addChild('payment');
		$creditCard = $payment->addChild('creditCard');

		$creditCard->addChild("cardNumber", $this->obj->__get('cardNumber'));
		$creditCard->addChild("expirationDate", $this->obj->__get('expiration'));
		$creditCard->addChild("cardCode", $this->obj->__get('cvv'));

		return $this;
	}

	/**
	 * Set Order Details
	 *
	 * @return Transaction
	 */
	private function setOrderDetails(): Transaction {
		$order = $this->xml->transactionRequest->addChild("order");
		$order->addChild("invoiceNumber", $this->ref);
		$order->addChild("description", 'Your Order - ' . $this->ref);

		return $this;
	}

	/**
	 * Set Line Items
	 *
	 * @return Transaction
	 */
	private function setLineItems(): Transaction {
		$items = $this->obj->__get('items');

		$lineItems = $this->xml->transactionRequest->addChild("lineItems");

		for($i=0; $i < count($items); $i++) {
			$line = $lineItems->addChild('lineItem');

			$line->addChild("itemId", $items[$i]["id"]);
			$line->addChild("name", $items[$i]["name"]);
			$line->addChild("description", $items[$i]["description"]);
			$line->addChild("quantity", $items[$i]["quantity"]);
			$line->addChild("unitPrice", $items[$i]["price"]);
		}

		return $this;
	}

	/**
	 * Set Tax Details
	 *
	 * @return Transaction
	 */
	private function setTaxDetails(): Transaction {
		$tax_data = $this->obj->__get('tax');

		$tax = $this->xml->transactionRequest->addChild('tax');

		$tax->addChild("amount", $tax_data["amount"]);
		$tax->addChild("name", $tax_data["name"]);
		$tax->addChild("description", $tax_data["description"]);

		return $this;
	}

	/**
	 * Set Duty Details
	 *
	 * @return Transaction
	 */
	private function setDutyDetails(): Transaction {
		$duty_data = $this->obj->__get('duty');

		$duty = $this->xml->transactionRequest->addChild("duty");

		if(!$duty_data) {
			$duty->addChild("amount", '0.00');
			$duty->addChild("name", 'Fee');
			$duty->addChild("description", 'Duty Fee');
		} else {
			$duty->addChild("amount", $duty_data["amount"] ?? '0.00');
			$duty->addChild("name", $duty_data["name"] ?? 'Fee');
			$duty->addChild("description", $duty_data["description"] ?? 'Duty Fee');
		}

		return $this;
	}

	/**
	 * Set Shipping Details
	 *
	 * @return Transaction
	 */
	private function setShippingDetails(): Transaction {
		$ship_data = $this->obj->__get("shipping_cost");

		$shipping = $this->xml->transactionRequest->addChild("shipping");

		$shipping->addChild("amount", $ship_data["amount"]);
		$shipping->addChild("name", $ship_data["name"]);
		$shipping->addChild("description", $ship_data["description"]);

		return $this;
	}

	/**
	 * Set Miscellaneous Details
	 *
	 * @return Transaction
	 */
	private function setMiscDetails(): Transaction {
		// Not Request unless B2B
		$this->xml->transactionRequest->addChild("poNumber", $this->obj->__get("poNumber") ?? '000000');

		$customer = $this->xml->transactionRequest->addChild("customer");
		$customer->addChild("id", $this->obj->__get("customerId"));

		return $this;
	}

	/**
	 * Set Bill To
	 *
	 * @return Transaction
	 */
	private function setBillTo(): Transaction {
		$billing_data = $this->obj->__get("billing");

		$billing = $this->xml->transactionRequest->addChild("billTo");

		$billing->addChild("firstName", $billing_data["firstName"]);
		$billing->addChild("lastName", $billing_data["lastName"]);
		$billing->addChild("company", $billing_data["company"]);
		$billing->addChild("address", $billing_data["address"]);
		$billing->addChild("city", $billing_data["city"]);
		$billing->addChild("state", $billing_data["state"]);
		$billing->addChild("zip", $billing_data["zip"]);
		$billing->addChild("country", $billing_data["country"]);

		return $this;
	}

	/**
	 * Set Ship To
	 *
	 * @return Transaction
	 */
	private function setShipTo(): Transaction {
		$shipping_data = $this->obj->__get("shipping");

		$shipping = $this->xml->transactionRequest->addChild("shipTo");

		$shipping->addChild("firstName", $shipping_data["firstName"]);
		$shipping->addChild("lastName", $shipping_data["lastName"]);
		$shipping->addChild("company", $shipping_data["company"]);
		$shipping->addChild("address", $shipping_data["address"]);
		$shipping->addChild("city", $shipping_data["city"]);
		$shipping->addChild("state", $shipping_data["state"]);
		$shipping->addChild("zip", $shipping_data["zip"]);
		$shipping->addChild("country", $shipping_data["country"]);

		return $this;
	}

	/**
	 * Set Customer IP
	 *
	 * @return Transaction
	 */
	private function setCustomerDetails(): Transaction {
		$this->xml->transactionRequest->addChild("customerIP", $_SERVER["REMOTE_ADDR"] ?? '0.0.0.0');

		return $this;
	}

	/**
	 * Set Transaction Request
	 *
	 * @return Transaction
	 */
	private function createTransactionRequest(): Transaction {
		$this->setReferenceId('cc')
			->setMerchantDetails()
			->setTransactionDetails()
			->setCreditCardDetails()
			->setOrderDetails()
			->setLineItems()
			->setTaxDetails()
			->setDutyDetails()
			->setShippingDetails()
			->setMiscDetails()
			->setBillTo()
			->setShipTo()
			->setCustomerDetails();

		return $this;
	}

	/**
	 * Set Refund Request
	 * 		Requires Transaction ID
	 *
	 * @return Transaction
	 */
	private function createRefundRequest(): Transaction {
		$this->setReferenceId("re")
			->setMerchantDetails()
			->setRefundDetails();

		return $this;
	}

	/**
	 * Set Void Request
	 * 		Requires Transaction ID
	 *
	 * @return Transaction
	 */
	private function createVoidRequest(): Transaction {
		$this->setReferenceId("vo")
			->setMerchantDetails()
			->setVoidDetails();

		return $this;
	}

	/**
	 * Execute Responses
	 *
	 * @return Response
	 */
	public function execute(): Library\Response {
		$request = new Library\Request($this->xml);

		return $request->process();
	}

	/**
	 * Get XML
	 * 		Debugging
	 *
	 * @return string
	 */
	public function getXML(): string {
		return $this->xml->asXML();
	}
}