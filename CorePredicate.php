<?php
namespace KH\CoreDBBundle;

class CorePredicate {

	// Property to condition against
	private $property = null;

	// The Conditional to compare with
	private $conditional = null;

	// What the property should or should not be (depending on conditional)
	private $value = null;

	// If multiple Predicates are present, this glue can (and will) be used and THEN this predicate is written
	private $glue = null;

	/**
	 * Creates a new Filter Predication (Conditional)
	 * @property String $property The property/field name
	 * @property String $value the value of the property to match against
	 * @property Const $conditional The conditional representaiton to argue the value vs. the property with
	 * @property Const $glue If multiple predicates are presented, this is the condition this predicate should be used in sequence
	 * @example new CorePredicate("id", 1); // would equal: WHERE `id` = 1;
	 * @example array(new CorePredicate("firstname", "Ron%", CorePredicate::LIKE), new CorePredicate("lastname", "Howard", CorePredicateCondition::EQUALS, CorePredicateGlue::AND)); // equivilent to: WHERE `firstname` LIKE "Ron%" AND `lastname` = "Howard"
	 */
	function __construct($property, $value, $conditional = CorePredicateCondition::EQUALS, $glue = CorePredicateGlue::GLUE_AND) {
		$this->setProperty($property);
		$this->setValue($value);
		$this->setConditional($conditional);
		$this->setGlue($glue);
	}

	public function setProperty($str) { $this->property = $str; }
	public function setValue($str) { $this->value = $str; }
	public function setConditional($str) { $this->conditional = $str; }
	public function setGlue($glue) { $this->glue = $glue; }

	public function getProperty() { return $this->property; }
	public function getValue() { return $this->value; }
	public function getConditional() { return $this->conditional; }
	public function getGlue() { return $this->glue; }

}