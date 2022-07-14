<?php 
Class Lang {

	private $list;

	// List files in a directory.
	private static function listFiles($file) {
		foreach(
			array_slice(array_slice(scandir($file),2),0, count(array_slice(scandir($file),2))) as $key => $val) {
				$list[basename($val, '.json')] = (array) json_decode(file_get_contents(__RACINE__.'/lang/'.$val));
			}
		return $list;
	}

	// Initializes the list of language files
	public function __construct() {
		$this->list = Self::listFiles('lang');
	}

	// Returns the list of items.
	public function getList() {
		return $this->list;
	}
}

$lang = (new Lang)->getList();