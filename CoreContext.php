<?php
namespace KH\CoreDBBundle;

use Symfony\Component\HttpKernel\Exception\FatalErrorException;
class CoreContext {

	private $store;

	private $models = array();

	private $deletes = array();

	private $queries = array();

	protected $storePath = "./";
	
	/**
	 * Establishes a connection to the sqlite3 database
	*/
	function __construct($DatabasePath) {
		$this->storePath = __DIR__."/Resources/datastores";
		chmod($this->storePath, 0777); // make SURE directory is writeable
		$path = $this->storePath."/".$DatabasePath.".coredb";
		$this->store = new \SQLite3($path, SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE);
		@chmod($path, 0777); // attempt to ensure the file is writable
	}

	public function getStore() { return $this->store; }

	/**
	 * Pass a reference of a model or struct, and it will automatically create a table based on the data.
	 * @property CoreModel $model A reference to a CoreModel object model
	 * @property Boolean $deleteIfExists Will delete the table is it already exists, so as not to throw an exception due to a colission
	 */
	public function createEntityBasedOnModel($model, $deleteIfExists = true) {

		if($deleteIfExists)
			$this->store->exec("DROP TABLE IF EXISTS ".get_class($model));
			
		$properties = array();
		foreach($model as $property=>$value) {
			$type = CoreEntityProperty::TEXT;
				
			if($property == "id" || is_int($value)) $type = CoreEntityProperty::NUMBER;
			else if(is_string($value)) $type = CoreEntityProperty::TEXT;
			else $type = CoreEntityProperty::
				
			$properties[] = CoreEntityProperty::Create($property, $type, (($property == "id")? true : false ) );
		}
			
		$db = CoreDB::CreateEntity(&$this,
				CoreEntityDescription::Create(get_class($model), $properties)
		);

	}


	/**
	 *
	 */
	public function executeFetchRequest(CoreFetchRequest $request) {

		/// prepare select
		$sql = "SELECT ";

		// get selected properties
		if(count($request->getPropertes())>0)
			$sql .= implode(", ", $request->getPropertes());
		else
			$sql .= "*";

		$sql .= " FROM ".$request->getEntity();

		// check predicates
		if(count($request->getPredicates())>0) {

			$predicates = array();
			foreach($request->getPredicates() as $count=>$predicate)
				$predicates[] = (( $count >= 1 )? $predicate->getGlue()." " : null ).$predicate->getProperty()." ".$predicate->getConditional()." ".((is_int($predicate->getValue()))? $predicate->getValue() : "'".$predicate->getValue()."'" );
				
			$sql .= " WHERE ".implode(" ", $predicates);

		}

		if(count($request->getDescriptors())>0) {

			$descriptors = array();
			foreach($request->getDescriptors() as $sort)
				$descriptors[] = $sort->getProperty()." ".$sort->getDirection();

			$sql .= " ORDER BY ".implode(",", $descriptors);
		}


		// execute the query
		$sql = $this->store->query($sql);


		// tracking
		$this->queries[] = $sql;

		$return = array();
		while($rec = $sql->fetchArray(SQLITE3_ASSOC)) {

			if(class_exists($request->getEntity(), true)) {
				// we can use the host class

				// get base entity
				$en = $request->getEntity();

				// use that to instantiate new ghost model
				$n = new $en(&$this);

				// populate the model with fetched information
				foreach($rec as $k=>$v) $n->{$k} = $v;

				// add
				$return[] = $n;


			} else {
				
				$return[] = (object)$rec;
				
			}


		}


		// bring back all the infos
		return $return;


	}

	/**
	 * Gathers all Inserts, Updates, and Deletions, builds the series of queries, and executes them one by one
	 */
	public function save($autoclean = false) {

		// Compile all queries
		$sqls = array();

		// Go through each collected model, and create their specific SQL statements
		foreach($this->models as $model) {

			$class = get_class($model);
			$class = explode('\\', $class);
			$class = array_pop($class);
			
			// Determine if this is a INSERT or UPDATE statment
			$sql = ((!$model->id)? "INSERT INTO" : "UPDATE")." ".$class;

			// the hard part, the automatic structure
			if(!$model->id) {

				// insert

				$keys = array();
				$values = array();
				foreach($model as $key=>$property)
				if($key != "id") { // pointless but we dont want the ID to be in here
					$keys[] = $key;
					$values[] = ((is_int($property))? $property : "'".$property."'" );
				}

				$sql .= " (".implode(",", $keys).") VALUES (".implode(", ", $values).")";

			} else if($model->id && !$model->getWillDelete()) {

				// update

				$parts = array();
				foreach($model as $key=>$property)
				if($key != "id") // we do not want to include the ID in the update list
					$parts[] = $key."=".((is_int($property))? $property : "'".$property."'" );

				$sql .= " SET ".implode(", ", $parts)." WHERE id=".$model->id;

			} else if($model->id && $model->getWillDelete()) {
				
				$sql = "DELETE FROM ".$class." WHERE id=".$model->id;
				
			}

			// stash the query into the queue
			$sqls[] = $sql;
		}

		foreach($sqls as $index=>$query) {

			//echo "Executing: $query";
			$this->store->exec($query);

			// tracking
			$this->queries[] = $query;

		}

		// models are retained unless autoclean is set to true
		if($autoclean) $this->clear();

	}


	public function addModel($ref) {
		$this->models[] = $ref;
	}


	public function clear() {
		$this->models = array();
	}

}