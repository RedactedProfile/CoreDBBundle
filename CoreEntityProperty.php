<?php
namespace KH\CoreDBBundle;

class CoreEntityProperty {

	const TEXT = "TEXT";
	const NUMBER = "INTEGER";
	const BLOB = "BLOB";
	const DATE = "TEXT";

	public $name;
	public $type;
	public $primary = false;

	function __construct($name = null, $type = null, $primary = false) {
		$this->setName($name)->setType($type)->setPrimary($primary);
	}

	public function setName($str) { $this->name = $str; return $this; }
	public function setType($const) { $this->type = $const; return $this; }
	public function setPrimary($bool = true) { $this->primary = $bool; return $this; }

	public static function Create($name = null, $type = null, $primary = false) {
		return new CoreEntityProperty($name, $type, $primary);
	}
}