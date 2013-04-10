<?php
namespace KH\CoreDBBundle;

class CoreEntityDescription {
	public $name;
	public $properties = array();

	function __construct($name, $properties = array()) {
		$this->name = $name;
		$this->properties = $properties;
	}

	public function addProperty(CoreEntityProperty $property) {
		$this->properties[] = $property;
		return $this;
	}

	public static function Create($name, $properties = array()) {
		return new CoreEntityDescription($name, $properties);
	}
}