<?php

namespace Incubateiq\Gateway\Transaction;

class Logger {
	/**
	 * Message Array
	 *
	 * @var array
	 */
	protected array $messages = [];

	public function __construct() {}

	/**
	 * Log Message
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function log(string $message): void {
		$this->messages[] = [
			date('Y-m-d H:i:s'),
			$message
		];
	}

	/**
	 * Get Log
	 *
	 * @return string|null
	 */
	public function getLog(): ?string {
		$str = '';

		if(count($this->messages) > 0) {
			foreach($this->messages as $msg) {
				$str .= $msg[0] . ': ' . $msg[1];
			}

			return $str;
		} else {
			return null;
		}
	}

	public function store() {
		// TODO Create file to store Transaction Messages
		// TODO Create store in SQL
	}
}