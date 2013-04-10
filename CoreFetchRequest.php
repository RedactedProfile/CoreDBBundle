<?php
namespace KH\CoreDBBundle;

class CoreFetchRequest {

	// the table
	private $entity = null;

	// conditions
	private $predicates = array();

	// sorting
	private $descriptors = array();

	// fields to fetch
	private $properties = array();

	// record limit
	private $limit = null;

	// automatic record offset with limit
	private $page  = null;


	/**
	 * Creates a new CoreDB Fetch Request
	 * @property String $withEntityName the name of the Entity to request data from
	 */
	function __construct($withEntityName = null) {
		$this->setEntity($withEntityName);
	}

	/**
	 * Sets/Overrides the specified Entity (table)
	 */
	public function setEntity($entityName) {
		$this->entity = $entityName;
		return $this;
	}

	/**
	 * Sets/Overrides existing Sorting description
	 * @property $sort CoreSortDescriptor a single sort descriptor object
	 */
	public function setSortDescriptor(CoreSortDescriptor $sort) {
		$this->descriptors = array($sort);
		return $this;
	}

	/**
	 * Sets/Overrides existing filter predication (conditional)
	 * @property $predicate CorePredicate a single predicate object (condition)
	 */
	public function setPredicate(CorePredicate $predicate) {
		$this->predicates = array($predicate);
		return $this;
	}

	/**
	 * Sets/Overrides existing Sorting descroption with a series of of new Sort descroptions
	 * @property $sortDescriptors An array of Sort Descriptors. Any object that is not a CoreSortDescriptor object will be ignored.
	 */
	public function setSortDescriptors($sortDescriptors) {
		$this->descriptors = array();
		foreach($sortDescriptors as $desc) if(get_class($desc) == "CoreSortDescriptor") $this->descriptors[] = $desc;
		return $this;
	}

	/**
	 * Sets/Overrides existing filter predicates (conditionals)
	 * @property $predicates An array of CorePredicates. Any object that is not a CorePredicate object will be ignored.
	 */
	public function setPredicates($predicates) {
		$this->predicates = array();
		foreach($predicates as $p) if(get_class($p) == "CorePredicate") $this->predicates[] = $p;
		return $this;
	}

	/**
	 * Sets/Overrides the precise properties (fields) to fetch. If not used, default "*" will be used.
	 * @property $properties Array an array of strings with property (field) names
	 */
	public function setPropertiesToFetch($properties = array("*")) {
		$this->properties = $properties;
		return $this;
	}

	/**
	 * Sets/Overrides a limit of records to fetch
	 * @property $integer int The limit of records to fetch as a number
	 */
	public function setFetchLimit($integer) {
		$this->limit = $integer;
		return $this;
	}

	/**
	 * Sets/Overrides a page for automatic record offsetting
	 * @property $integer int the explicit page number. If not used, will not take any effect.
	 */
	public function setPage($integer) {
		$this->page = $integer;
		return $this;
	}

	public function getEntity() { return $this->entity; }
	public function getPropertes() { return $this->properties; }
	public function getDescriptors() { return $this->descriptors; }
	public function getPredicates() { return $this->predicates; }

	public function clearSortDescriptors() { $this->descriptors = array(); return $this; }
	public function clearPredicates() { $this->predicates = array(); return $this; }
	
	public function clean() { $this->clearSortDescriptors()->clearPredicates(); return $this; }
	
}