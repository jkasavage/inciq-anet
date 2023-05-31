<?php

namespace Incubateiq\Gateway\Transaction\Base;

use Incubateiq\Gateway\Transaction\Base\Env as Constants;
use Incubateiq\Gateway\Transaction\Base as Library;
use SimpleXMLElement;

class Request {
	/**
	 * XML Request
	 *
	 * @var SimpleXMLElement
	 */
	protected SimpleXMLElement $xml;

	public function __construct(SimpleXMLElement $xml) {
		$this->xml = $xml;
	}

	/**
	 * Process Request
	 *
	 * @return Library\Response
	 */
	public function process(): Library\Response {
		$ch = curl_init();

		$headers = [
			"Content-Type: text/xml",
			"Authorization: Basic " . base64_encode(Constants::ID . ":" . Constants::KEY),
			"Content-Length: " . strlen($this->xml->asXML())
		];

		curl_setopt($ch, CURLOPT_URL, Constants::URL . '/xml/v1/request.api');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml->asXML());
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 45);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($ch, CURLOPT_CAINFO, Env::CA);

		// v DISABLE (COMMENT OUT) FOR PRODUCTION v
		//curl_setopt($ch, CURLOPT_VERBOSE, true);
		//$verbose = fopen("php://stdout", "w+");
		//curl_setopt($ch, CURLOPT_STDERR, $verbose);
		// ^ DISABLE (COMMENT OUT) FOR PRODUCTION ^

		$response = curl_exec($ch);

		$err = curl_error($ch);

		if(!$err) {
			$xml = @simplexml_load_string($response);

			curl_close($ch);

			return new Library\Response($xml);
		} else {
			$text = simplexml_load_string('<?xml version="1.0" ?><error>' . $err . '</error>');

			curl_close($ch);

			return new Library\Response($text);
		}
	}
}