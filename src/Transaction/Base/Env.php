<?php

namespace Incubateiq\Gateway\Transaction\Base;

class Env {
	/**
	 * Login ID
	 * 		Replace this value based on Project
	 *
	 * @var string
	 */
	const ID = '5TaH77qfMs';

	/**
	 * Login Key
	 * 		Replace this value based on Project
	 *
	 * @var string
	 */
	const KEY = '9rvy467cHV726zSV';

	/**
	 * API URL
	 * 		Change for Production
	 *
	 * @var string
	 */
	const URL = 'https://apitest.authorize.net';

	/**
	 * Authorize.net SSL Certificate
	 *
	 * @var string
	 */
	const CA = __DIR__ . '/cert/cert.pem';
}