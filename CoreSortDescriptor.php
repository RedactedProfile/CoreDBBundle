<?php
namespace KH\CoreDBBundle;

class CoreSortDescriptor {

	private $property = null;
	private $direction = null;

	/**
	 * Creates a new Sorting Description
	 * @property String $property a string representation of the property (field) to sort by
	 * @property Const $withDirection a String representation of the direction of the sort: ASCENDING / DESCENDING
	 */
	function __construct($property, $withDirection = CoreSort::ASCENDING) {
		$this->setProperty($property);
		$this->setDirection($withDirection);
	}

	public function setProperty($property) { $this->property = $property; }
	public function setDirection($withDirection) { $this->direction = $withDirection; }

	public function getProperty() { return $this->property; }
	public function getDirection() { return $this->direction; }

}