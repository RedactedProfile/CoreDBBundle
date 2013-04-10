<?php
namespace KH\CoreDBBundle;

class CoreError {
	private $code = 0;
	private $message = null;

	function __construct($code, $message) {
		$this->code = $code;
		$this->message = $message;
	}

	public function describe() {
		return "Error: ".$this->code."; with Message: '".$this->message."'";
	}
}