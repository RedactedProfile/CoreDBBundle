<?php
namespace KH\CoreDBBundle;

class CoreModel {

	public $id;
	
	protected $willDelete = false;

	public function __construct(CoreContext $context) {
		$context->addModel(&$this);
	}
	
	public function markToDelete() {
		$this->willDelete = true;
	}
	
	public function getWillDelete() {
		return $this->willDelete;
	}
	
	static public function Factory(CoreContext $context, $array = array()) {
		$reflection = get_called_class();
		$items = array();
		foreach($array as $obj) {
			$item = new $reflection($context);
			foreach($obj as $k=>$v) $item->{$k} = $v;
			$items[] = $item;
		}
		
		return $items;
	}
	
	static private function getReflection() {
		return new ReflectionClass(get_called_class());
	}
	
	

}