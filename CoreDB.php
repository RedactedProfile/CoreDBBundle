<?
namespace KH\CoreDBBundle;
/*
CoreDB
A "CoreData" inspired managed object SQLLess wrapper for SQLite3
Written by: Kyle Harrison
http://kyleharrison.ca

See: README.md for examples
*/

class CoreDB {
	
	function __construct() {

	}

	public static function CreateContext($DatabasePath) {
		return new CoreContext($DatabasePath);
	}


	public static function CreateEntity($context, CoreEntityDescription $entity) {
		$store = $context->getStore();

		$store->exec("DROP TABLE IF EXISTS ".$entity->name);

		$sql = "CREATE TABLE ".$entity->name." (";

		$props = array();
		foreach($entity->properties as $property)
			$props[] = $property->name . " " . $property->type . (($property->primary)? " PRIMARY KEY" : null );

		$sql .= implode(",
			", $props);

		$sql .= ")";

		$store->exec($sql);

		return $entity;
	}
	
	public static function CreateFetchRequest($context) {
		return new CoreFetchRequest();
	}
	
	public static function CreateSortDescriptor($property, $withDirection = CoreSort::ASCENDING) {
		return new CoreSortDescriptor($property, $withDirection);
	}
	

}
