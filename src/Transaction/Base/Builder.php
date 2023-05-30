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
	 * Request Data
	 *
	 * @var array
	 */
	protected array $data;

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	protected string $ref;

	/**
	 * List of Child Elements for nested Nodes
	 *
	 * @var array|string[]
	 */
	private array $children = [
		"lineItems" => "lineItem"
	];

	public function __construct(string $requestType, array $data=null) {
		$this->xml = new XML('<?xml version="1.0" encoding="UTF-8"?>' . "<" . $requestType . "></" . $requestType . ">");
		$this->xml->addAttribute('xmlns', "AnetApi/xml/v1/schema/AnetApiSchema.xsd");

		$this->data = $data;
		$this->buildRequest();
	}

	/**
	 * Build Request from Data (Array)
	 *
	 * @return void
	 */
	private function buildRequest(): void {
		$this->setMerchantAuthentication();

		if($this->data) {
			foreach($this->data as $key=>$value) {
				$this->addNode($this->xml, $key, $value);
			}
		}
	}

	/**
	 * Add Node to Request
	 *
	 * @param XML $parent
	 * @param string $node
	 * @param string|array $value
	 *
	 * @return void
	 */
	private function addNode(XML $parent, string $node, string|array $value): void {
		if(!is_array($value)) {
			if($node === "refId") {
				$this->setRefId($value);
				$parent->addChild($node, $this->ref);
			} else {
				$parent->addChild($node, $value);
			}
		} else {
			$spawn = $parent->addChild($node);

			foreach($value as $k=>$v) {
				if(!is_array($v)) {
					$spawn->addChild($k, $v);
				} else {
					if(!is_integer($k)) {
						$this->addNode($spawn, $k, $v);
					} else {
						$this->addNode($spawn, $this->children[$node], $v);
					}
				}
			}
		}
	}

	/**
	 * Set Merchant Authentication
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
		$unique = $ref . time();
		$this->ref = $unique;
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
	 * View XML for Debugging
	 *
	 * @return string
	 */
	public function debug(): string {
		return $this->xml->asXML();
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