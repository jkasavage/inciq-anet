<?php

namespace Incubateiq\Gateway\Transaction\Base;

use SimpleXMLElement as XML;
use Incubateiq\Gateway\Transaction\Base\Env as Env;

class Builder {
	/**
	 * XML Object (SimpleXMLElement)
	 *
	 * @var XML
	 */
	protected XML $xml;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	protected string $ref;

	public function __construct(string $requestType) {
		$this->xml = new XML("<" . $requestType . "></" . $requestType . ">");
		$this->xml->addAttribute('xmlns', "AnetApi/xml/v1/schema/AnetApiSchema.xsd");
	}

	/**
	 * Set Merchant Authentication
	 *
	 * @return $this
	 */
	public function setMerchantAuthentication(): Builder {
		$merchant = $this->xml->addChild("merchantAuthentication");
		$merchant->addChild("name", Env::ID);
		$merchant->addChild("transactionKey", Env::KEY);

		return $this;
	}

	/**
	 * Set Reference ID
	 *
	 * @param string $ref
	 *
	 * @return $this
	 */
	public function setRefId(string $ref): Builder {
		$this->xml->addChild("refId", $ref . time());

		return $this;
	}

	/**
	 * Get Reference ID
	 *
	 * @return string
	 */
	public function getRefId(): string {
		return $this->ref;
	}

	/**
	 * Set Bill To / Ship To / Address Nodes
	 *
	 * @param XML $parent
	 * @param array $info
	 * @param string $type
	 *
	 * @return $this
	 */
	public function setBillToShipTo(XML $parent, array $info, string $type="billTo"): Builder {
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

		return $this;
	}

	/**
	 * Get XML Request
	 *
	 * @return XML
	 */
	public function getRequest(): XML {
		return $this->xml;
	}
}